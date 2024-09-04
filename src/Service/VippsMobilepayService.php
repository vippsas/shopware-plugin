<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Service;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\StateMachine\StateMachineRegistry;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vipps\Mobilepay\Util\VippsMobilepayLogger;

class VippsMobilepayService
{
    /** @var $clients Client[] */
    protected array $clients = [];

    public function __construct(
        protected SystemConfigService               $systemConfigService,
        protected EntityRepository                  $languageRepository,
        protected EntityRepository                  $orderRepository,
        protected EntityRepository                  $salesChannelRepository,
        protected OrderTransactionStateHandler      $transactionStateHandler,
        protected StateMachineRegistry              $stateMachineRegistry,
        protected SeoUrlPlaceholderHandlerInterface $seoUrlPlaceholderHandler,
        protected EventDispatcherInterface          $eventDispatcher,
        protected VippsMobilepayLogger              $vippsMobilepayLogger,
        protected TranslatorInterface               $translator
    ) {
    }

    public function isConfigValid(array $config, string $base_uri): bool
    {
        try {
            $client = new Client([
                'base_uri' => $base_uri,
                'headers' => array_merge([
                    "Accept" => "*/*",
                    "Host" => preg_replace("(^https?://)", "", $base_uri),
                    "cache-control" => "no-cache",
                    "Content-Type" => "application/json",
                    "Vipps-System-Name" => $_SERVER['HTTP_ORIGIN'],
                    "Vipps-System-Version" => InstalledVersions::getVersion('shopware/core'),
                    "Vipps-System-Plugin-Name" => "Shopware - Vipps Mobilepay ePayment",
                    "Vipps-System-Plugin-Version" => $this->getVersion(),
                ], $config),
            ]);

            $response = $client->request('POST', 'accesstoken/get');

            $jsonResponse = json_decode($response->getBody()->getContents());
            if (isset($jsonResponse->error)) {
                return false;
            }
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::IS_CONFIG_VALID_ERROR,
                [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTrace(),
                ]
            );
            return false;
        }
    }

    public function serializeOrderLineData($lineItem, SalesChannelContext $salesChannelContext): array
    {
        $salesChannel = $salesChannelContext->getSalesChannel();
        $domainUrl = $salesChannel->getDomains()->get($salesChannelContext->getDomainId())->getUrl();
        $orderLines = [];
        foreach ($lineItem as $item) {
            if (isset($lineItemGenerateEvent->result)) {
                if (!empty($lineItemGenerateEvent->result)) {
                    $orderLines[] = $lineItemGenerateEvent->result;
                }
                continue;
            }
            $taxAmount = $item->getPrice()->getCalculatedTaxes()->reduce(
                fn($carry, $tax) => $carry + round($tax->getTax() * 100, 0),
                0
            );
            $total = round($item->getPrice()->getTotalPrice() * 100, 0);
            $totalExclTax = $total - $taxAmount;

            $taxRate = $totalExclTax > 0 ?
                round(($total / $totalExclTax - 1) * 100) :
                0;
            $taxRate = round($taxRate, 2);
            $taxRate = $taxRate * 100;
            switch ($item->getType()) {
                case LineItem::PRODUCT_LINE_ITEM_TYPE:
                    $url = $this->seoUrlPlaceholderHandler->generate(
                        'frontend.detail.page',
                        ['productId' => $item->getReferencedId()]
                    );
                    $absoluteUrl = $this->seoUrlPlaceholderHandler->replace(
                        $url,
                        $domainUrl,
                        $salesChannelContext
                    );
                    $orderLines[] = [
                        'name' => $item->getLabel(),
                        'id' => $item->getReferencedId(),
                        'totalAmount' => $total,
                        'totalAmountExcludingTax' => $totalExclTax,
                        'totalTaxAmount' => $taxAmount,
                        'taxRate' => (int)$taxRate,
                        'unitInfo' => [
                            'unitPrice' => round($item->getPrice()->getUnitPrice() * 100, 0),
                            'quantity' => (string)$item->getQuantity(),
                            'quantityUnit' => 'PCS'
                        ],
                        'productUrl' => $absoluteUrl,
                    ];
                    break;
                case LineItem::DISCOUNT_LINE_ITEM:
                case LineItem::PROMOTION_LINE_ITEM_TYPE:
                    foreach ($item->getPrice()->getCalculatedTaxes() as $calculatedTax) {
                        $orderLines[] = [
                            'reference' => $item->getReferencedId() . "-tax-rate-{$calculatedTax->getTaxRate()}",
                            'type' => 'discount',
                            'name' => $item->getLabel(),
                            'quantity' => $item->getQuantity(),
                            'unit_price' => 0,
                            'tax_rate' => round($calculatedTax->getTaxRate() * 100, 0),
                            'total_discount_amount' => round($calculatedTax->getPrice() * 100 * -1, 0),
                            'total_amount' => round($calculatedTax->getPrice() * 100, 0),
                            'total_tax_amount' => round($calculatedTax->getTax() * 100, 0)
                        ];
                    }

                    break;
            }
        }
        return $orderLines;
    }

    public function createPayload(
        string $returnUrl,
        array $orderLines,
        float $totalPrice,
        string $orderId,
        ?string $phoneNumber,
        string $currency,
        string $salesChannelName
    ): array {
        return [
            'amount' => [
                "currency" => $currency,
                "value" => (int)($totalPrice * 100)
            ],

            'customer' => [
                'phoneNumber' => $phoneNumber,
            ],
            'paymentMethod' =>  [
                "type" => "WALLET",
            ],
            'reference' => $orderId,
            'returnUrl' => $returnUrl,
            'userFlow' => "WEB_REDIRECT",
            'paymentDescription' => $this->translator->trans(
                "vipps-mobilepay.payment-description",
                ['%STORE%' => $salesChannelName]
            ),
            "receipt" => [
                "orderLines" => $orderLines,
                "bottomLine" => [
                    "currency" => $currency
                ]
            ],
        ];
    }

    public function getVersion(): string
    {
        $filePath = sprintf('%s/%s', \dirname(__DIR__, 2), 'composer.json');
        $jsonString = file_get_contents($filePath);
        $data = json_decode($jsonString, true);
        return $data['version'];
    }

    public function phoneNumberConverter(?string $number, string $country): ?string
    {
        if (!$number) {
            return null;
        }

        if (str_starts_with($number, "00")) {
            $phoneNumber = substr($number, 4);
        } elseif (str_starts_with($number, "+")) {
            $phoneNumber = substr($number, 3);
        } elseif (strlen($number) > 8) {
            if (strlen($number) > 10) {
                $phoneNumber = substr($number, 3);
            } else {
                $phoneNumber = substr($number, 2);
            }
        } elseif (strlen($number) == 8) {
            $phoneNumber = $number;
        } else {
            return null;
        }

        $cleanNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

        switch ($country) {
            case 'DK':
                $cleanNumber = '45' . $cleanNumber;
                break;
            case 'NO':
                $cleanNumber = '47' . $cleanNumber;
                break;
            case 'FI':
                $cleanNumber = '358' . $cleanNumber;
                break;
        }

        return $cleanNumber;
    }

    public function getCurrency(string $orderId): string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('id', $orderId))
            ->addAssociation('currency')
            ->setLimit(1);

        /** @var OrderEntity $order */
        $order = $this->orderRepository->search($criteria, Context::createDefaultContext())
            ->first();

        return $order->getCurrency()->getIsoCode();
    }

    public function getDomain(string $salesChannelId): string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('salesChannelId', $salesChannelId))
            ->setLimit(1);

        /** @var SalesChannelDomainEntity $salesChannelDomain */
        $salesChannelDomain = $this->salesChannelRepository->search($criteria, Context::createDefaultContext())
            ->first();

        if ($salesChannelDomain) {
            return $salesChannelDomain->getUrl();
        }
        return "";
    }
}

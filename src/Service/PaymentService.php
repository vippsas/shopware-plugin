<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Service;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Level;
use Shopware\Core\Checkout\Cart\AbstractCartPersister;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Payment\Cart\AsyncPaymentTransactionStruct;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface;
use Shopware\Core\Checkout\Payment\Exception\AsyncPaymentFinalizeException;
use Shopware\Core\Checkout\Payment\Exception\AsyncPaymentProcessException;
use Shopware\Core\Checkout\Payment\Exception\CustomerCanceledAsyncPaymentException;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use stdClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Vipps\Mobilepay\Util\ConfigHelper;
use Vipps\Mobilepay\Util\VippsMobilepayLogger;

class PaymentService implements AsynchronousPaymentHandlerInterface
{
    public function __construct(
        protected OrderTransactionStateHandler      $orderTransactionStateHandler,
        protected SeoUrlPlaceholderHandlerInterface $seoUrlPlaceholderHandler,
        protected AbstractCartPersister             $cartPersister,
        protected EntityRepository                  $orderRepository,
        protected VippsMobilepayService             $vippsMobilepayService,
        protected ConfigHelper                      $configService,
        protected VippsMobilepayLogger              $vippsMobilepayLogger
    ) {
    }

    public function pay(
        AsyncPaymentTransactionStruct $transaction,
        RequestDataBag                $dataBag,
        SalesChannelContext           $salesChannelContext
    ): RedirectResponse {
        try {
            $this->getAccessToken($salesChannelContext->getSalesChannelId());
            $vippsResponse = $this->createPaymentRequest(
                $transaction->getOrder(),
                $transaction->getReturnUrl(),
                $salesChannelContext
            );
        } catch (GuzzleException $e) {
            $this->configService->setAccessToken(
                "",
                $salesChannelContext->getSalesChannelId()
            );
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::ERROR,
                [
                    "status" => $e->getCode(),
                    "message" => $e->getMessage(),
                    "trace" => $e->getTrace()

                ],
                $salesChannelContext->getSalesChannelId(),
                Level::Error->value
            );
            throw new AsyncPaymentProcessException(
                $transaction->getOrderTransaction()->getId(),
                "Request error when creating payment request @ Vipps",
                $e
            );
        }
        return new RedirectResponse($vippsResponse->redirectUrl, 302);
    }

    /**
     * @throws GuzzleException
     */
    public function finalize(
        AsyncPaymentTransactionStruct $transaction,
        Request                       $request,
        SalesChannelContext           $salesChannelContext
    ): void {
        $response = $this->getTransaction($transaction->getOrder()->getId(), $salesChannelContext->getSalesChannelId());
        switch ((string)$response->state) {
            case "AUTHORIZED":
                $this->cartPersister->delete(
                    $transaction->getOrderTransaction()->getId(),
                    $salesChannelContext
                );

                $this->orderTransactionStateHandler->authorize(
                    $transaction->getOrderTransaction()->getId(),
                    $salesChannelContext->getContext()
                );
                $this->vippsMobilepayLogger->logger(
                    VippsMobilepayLogger::ORDER_COMPLETE_SUCCESS,
                    [
                        "data" => $response
                    ],
                    $salesChannelContext->getSalesChannelId(),
                    Level::Debug->value
                );
                break;
            case "ABORTED":
                $this->vippsMobilepayLogger->logger(
                    VippsMobilepayLogger::ORDER_COMPLETE_CANCELLED,
                    [
                        "data" => $response
                    ],
                    $salesChannelContext->getSalesChannelId(),
                    Level::Debug->value
                );
                throw new CustomerCanceledAsyncPaymentException(
                    $transaction->getOrderTransaction()->getId()
                );
            default:
                $this->vippsMobilepayLogger->logger(
                    VippsMobilepayLogger::ORDER_COMPLETE_ERROR,
                    [
                        "data" => $response
                    ],
                    $salesChannelContext->getSalesChannelId(),
                    Level::Error->value
                );
                throw new AsyncPaymentFinalizeException(
                    $transaction->getOrder()->getId(),
                    (string)$response->state
                );
        }
    }

    /**
     * @throws GuzzleException
     */
    protected function getClient(string $salesChannelId): Client
    {
        $apiUrl = $this->configService->getApiUrl($salesChannelId);

        $clientId = $this->configService->getClientId($salesChannelId);

        $clientSecret = $this->configService->getClientSecret($salesChannelId);

        $subscriptionKey = $this->configService->getOCPPrimary($salesChannelId);

        $merchantSerialNumber = $this->configService->getMSN($salesChannelId);

        $token = $this->configService->getAccessToken($salesChannelId);

        $domain = $this->vippsMobilepayService->getDomain($salesChannelId);

        return $this->clients[$salesChannelId] = new Client([
            'base_uri' => $apiUrl,
            'headers' => array_merge([
                "Accept" => "*/*",
                "Host" => preg_replace("(^https?://)", "", $apiUrl),
                "cache-control" => "no-cache",
                "Content-Type" => "application/json",
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
                "Ocp-Apim-Subscription-Key" => $subscriptionKey,
                "Merchant-Serial-Number" => $merchantSerialNumber,
                "Idempotency-Key" => Uuid::randomHex(),
                "Vipps-System-Name" => $domain,
                "Vipps-System-Version" => InstalledVersions::getVersion('shopware/core'),
                "Vipps-System-Plugin-Name" => "Shopware - Vipps Mobilepay",
                "Vipps-System-Plugin-Version" => $this->vippsMobilepayService->getVersion()
            ], $token ? ["Authorization" => $token] : []),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getTransaction(string $id, string $salesChannelId): stdClass
    {
        $this->getAccessToken($salesChannelId);
        $apiUrl = $this->configService->getApiUrl($salesChannelId);
        $client = $this->getClient($salesChannelId);
        $paymentStateEndpoint = $apiUrl . '/epayment/v1/payments/' . $id;
        $response = $client->get($paymentStateEndpoint);
        $data = json_decode($response->getBody()->getContents());
        $this->vippsMobilepayLogger->logger(
            VippsMobilepayLogger::ORDER_GET_SUCCESS,
            [
                "data" => $data
            ],
            $salesChannelId,
            Level::Debug->value
        );
        return $data;
    }

    /**
     * @throws GuzzleException
     */
    public function capturePayment(string $id, string $salesChannelId, ?int $amount, ?string $currency): ?stdClass
    {
        $this->getAccessToken($salesChannelId);
        $apiUrl = $this->configService->getApiUrl($salesChannelId);
        $client = $this->getClient($salesChannelId);
        $capturePaymentEndpoint = $apiUrl . '/epayment/v1/payments/' . $id . '/capture';

        if (!$currency) {
            $currency = $this->vippsMobilepayService->getCurrency($id);
        }

        $response = $client->post($capturePaymentEndpoint, ['json' =>
            [
                "modificationAmount" => [
                    "currency" => $currency,
                    "value" => $amount
                ]
            ]
        ]);

        $context = Context::createDefaultContext();
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));

        /** @var $order OrderEntity */
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            return null;
        }
        $data = json_decode($response->getBody()->getContents());

        if ($data->aggregate->authorizedAmount->value !== $data->aggregate->capturedAmount->value) {
            $this->orderTransactionStateHandler->payPartially(
                $order->getTransactions()->first()->getId(),
                $context
            );
        } else {
            $this->orderTransactionStateHandler->paid(
                $order->getTransactions()->first()->getId(),
                $context
            );
        }

        $this->vippsMobilepayLogger->logger(
            VippsMobilepayLogger::ORDER_CAPTURE_SUCCESS,
            [
                "data" => $data
            ],
            $salesChannelId,
            Level::Debug->value
        );
        return $data;
    }

    /**
     * @throws GuzzleException
     */
    public function refundPayment(string $id, string $salesChannelId, ?int $amount, ?string $currency): ?stdClass
    {
        $this->getAccessToken($salesChannelId);
        $apiUrl = $this->configService->getApiUrl($salesChannelId);
        $client = $this->getClient($salesChannelId);
        $refundPaymentEndpoint = $apiUrl . '/epayment/v1/payments/' . $id . '/refund';

        if (!$currency) {
            $currency = $this->vippsMobilepayService->getCurrency($id);
        }

        $response = $client->post($refundPaymentEndpoint, ['json' =>
            [
                "modificationAmount" => [
                    "currency" => $currency,
                    "value" => $amount
                ]
            ]
        ]);

        $context = Context::createDefaultContext();
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));

        /** @var $order OrderEntity */
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            return null;
        }
        $data = json_decode($response->getBody()->getContents());

        if ($data->aggregate->refundedAmount->value !== $data->aggregate->capturedAmount->value) {
            $this->orderTransactionStateHandler->refundPartially(
                $order->getTransactions()->first()->getId(),
                $context
            );
        } else {
            $this->orderTransactionStateHandler->refund(
                $order->getTransactions()->first()->getId(),
                $context
            );
        }

        $this->vippsMobilepayLogger->logger(
            VippsMobilepayLogger::ORDER_REFUND_SUCCESS,
            [
                "data" => $data
            ],
            $salesChannelId,
            Level::Debug->value
        );
        return $data;
    }

    /**
     * @throws GuzzleException
     */
    public function cancelPayment(string $id, string $salesChannelId): ?stdClass
    {
        $this->getAccessToken($salesChannelId);
        $apiUrl = $this->configService->getApiUrl($salesChannelId);
        $client = $this->getClient($salesChannelId);
        $cancelPaymentEndpoint = $apiUrl . '/epayment/v1/payments/' . $id . '/cancel' ;
        $response = $client->post($cancelPaymentEndpoint);

        $context = Context::createDefaultContext();
        $criteria = new Criteria([$id]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));

        /** @var $order OrderEntity */
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            return null;
        }

        $this->orderTransactionStateHandler->cancel(
            $order->getTransactions()->first()->getId(),
            $context
        );

        $data = json_decode($response->getBody()->getContents());
        $this->vippsMobilepayLogger->logger(
            VippsMobilepayLogger::ORDER_REFUND_SUCCESS,
            [
                "data" => $data
            ],
            $salesChannelId,
            Level::Debug->value
        );
        return  $data;
    }

    /**
     * @throws GuzzleException
     */
    protected function createPaymentRequest(
        OrderEntity $order,
        string $returnUrl,
        SalesChannelContext $salesChannelContext
    ): ?stdClass {
        $apiUrl = $this->configService->getApiUrl($salesChannelContext->getSalesChannelId());
        $orderLines = $this->vippsMobilepayService->serializeOrderLineData(
            $order->getLineItems(),
            $salesChannelContext
        );
        $client =  $this->getClient($salesChannelContext->getSalesChannelId());

        $payload = $this->vippsMobilepayService->createPayload(
            $returnUrl,
            $orderLines,
            $order->getAmountTotal(),
            $order->getId(),
            $this->vippsMobilepayService->phoneNumberConverter(
                $order->getBillingAddress()->getPhoneNumber(),
                $order->getBillingAddress()->getCountry()->getIso()
            ),
            $order->getCurrency()->getIsoCode(),
            $salesChannelContext->getSalesChannel()->getName()
        );
        $paymentEndpoint = $apiUrl . '/epayment/v1/payments';
        $response = $client->post($paymentEndpoint, ['json' => $payload]);
        $data = json_decode($response->getBody()->getContents());
        $this->vippsMobilepayLogger->logger(
            VippsMobilepayLogger::CREATE_PAYMENT_SUCCESS,
            [
                "data" => $data
            ],
            $salesChannelContext->getSalesChannelId(),
            Level::Debug->value
        );
        return $data;
    }

    /**
     * @throws GuzzleException
     */
    protected function getAccessToken(string $salesChannelId): void
    {

        $token = $this->configService->getAccessToken($salesChannelId);

        $timestamp = $this->configService->getTokenExpire($salesChannelId);

        $baseUri = $this->configService->getApiUrl($salesChannelId);

        $client = $this->getClient(
            $salesChannelId
        );

        if (!$token || $timestamp <= time()) {
            $tokenEndpoint = $baseUri . "/accesstoken/get";
            $response = $client->post($tokenEndpoint);

            $jsonResponse = json_decode($response->getBody()->getContents());

            $token = $jsonResponse->token_type . ' ' . $jsonResponse->access_token;

            $this->configService->setAccessToken($token, $salesChannelId);
            $this->configService->setTokenExpire($jsonResponse->expires_on, $salesChannelId);
        }
    }
}

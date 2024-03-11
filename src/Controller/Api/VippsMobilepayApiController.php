<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Controller\Api;

use GuzzleHttp\Exception\GuzzleException;
use Monolog\Level;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vipps\Mobilepay\Service\PaymentService;
use Vipps\Mobilepay\Service\VippsMobilepayService;
use Vipps\Mobilepay\Util\VippsMobilepayLogger;

#[Route(defaults: ['_routeScope' => ['administration']])]
class VippsMobilepayApiController
{
    public function __construct(
        protected VippsMobilepayService        $vippsMobilepayService,
        protected PaymentService               $paymentService,
        protected EntityRepository             $orderRepository,
        protected VippsMobilepayLogger         $vippsMobilepayLogger,
    ) {
    }

    #[Route(path: '/api/_action/mobilepay-api/verify', name: 'api.mobilepay.verify')]
    public function check(RequestDataBag $dataBag): Response
    {
        $base_uri = $dataBag->get('VippsMobilepayEpayment.config.apiUrl');
        $config = [
            'client_id' => $dataBag->get('VippsMobilepayEpayment.config.vippsMobilepayClientId'),
            'client_secret' => $dataBag->get('VippsMobilepayEpayment.config.vippsMobilepayClientSecret'),
            'Merchant-Serial-Number' => $dataBag->get('VippsMobilepayEpayment.config.vippsMobilepayMSN'),
        ];

        $primarySubscriptionKey = $dataBag
            ->get('VippsMobilepayEpayment.config.vippsMobilepayOcpApimSubscriptionKeyPrimary');
        $secondarySubscriptionKey = $dataBag
            ->get('VippsMobilepayEpayment.config.vippsMobilepayOcpApimSubscriptionKeySecondary');

        $firstConfig = array_merge($config, ['Ocp-Apim-Subscription-Key' => $primarySubscriptionKey]);
        $secondConfig = array_merge($config, ['Ocp-Apim-Subscription-Key' => $secondarySubscriptionKey]);

        $isFirstConfigValid = $this->vippsMobilepayService->isConfigValid($firstConfig, $base_uri);
        $isSecondConfigValid = $this->vippsMobilepayService->isConfigValid($secondConfig, $base_uri);

        if ($isFirstConfigValid || $isSecondConfigValid) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::IS_CONFIG_VALID_SUCCESS,
                [
                    "PrimarySubscriptionKey" => $isFirstConfigValid,
                    "SecondarySubscriptionKey" => $isSecondConfigValid
                ],
                null,
                Level::Debug->value
            );
            return new Response('', Response::HTTP_OK);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws GuzzleException
     */
    #[Route(path: '/api/vipps/payments', name: 'api.vipps.payments', methods: ['GET'])]
    public function getPayments(Request $request): JsonApiResponse|Response
    {
        $orderId = $request->get('orderId');
        $order = $this->orderRepository->search(new Criteria([$orderId]), Context::createDefaultContext())->first();
        if (!$order) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::ORDER_GET_ERROR,
                [
                    "error" => "Could not find any orders"
                ],
                null,
                Level::Error->value
            );
            return new Response(status: 400);
        }
        /** @var OrderEntity $order  */
        $response = $this->paymentService->getTransaction($order->getId(), $order->getSalesChannelId());
        return new JsonApiResponse(json_encode((array) $response));
    }

    /**
     * @throws GuzzleException
     */
    #[Route(path: '/api/vipps/capture', name: 'api.vipps.capture', methods: ['POST'])]
    public function capture(Request $request): JsonApiResponse|Response
    {
        $orderId = $request->get('orderId');
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $context = Context::createDefaultContext();
        $criteria = new Criteria([$orderId]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::ORDER_GET_ERROR,
                [
                    "error" => "Could not find any orders"
                ],
                null,
                Level::Error->value
            );
            return new Response(status: 400);
        }

        /** @var $order OrderEntity */
        $response = $this->paymentService->capturePayment(
            $orderId,
            $order->getSalesChannelId(),
            $amount,
            $currency
        );

        return new JsonApiResponse(json_encode($response));
    }

    /**
     * @throws GuzzleException
     */
    #[Route(path: '/api/vipps/refund', name: 'api.vipps.refund', methods: ['POST'])]
    public function refund(Request $request): JsonApiResponse|Response
    {
        $orderId = $request->get('orderId');
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $context = Context::createDefaultContext();
        $criteria = new Criteria([$orderId]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::ORDER_GET_ERROR,
                [
                    "error" => "Could not find any orders"
                ],
                null,
                Level::Error->value
            );
            return new Response(status: 400);
        }

        /** @var $order OrderEntity */
        $response = $this->paymentService->refundPayment(
            $orderId,
            $order->getSalesChannelId(),
            $amount,
            $currency
        );

        return new JsonApiResponse(json_encode($response));
    }

    /**
     * @throws GuzzleException
     */
    #[Route(path: '/api/vipps/cancel', name: 'api.vipps.cancel', methods: ['POST'])]
    public function cancel(Request $request): JsonApiResponse|Response
    {
        $orderId = $request->get('orderId');
        $context = Context::createDefaultContext();
        $criteria = new Criteria([$orderId]);
        $criteria->addAssociation('transactions')
            ->addSorting(new FieldSorting('transactions.createdAt', FieldSorting::DESCENDING));
        $order = $this->orderRepository->search($criteria, $context)->first();
        if (!$order) {
            $this->vippsMobilepayLogger->logger(
                VippsMobilepayLogger::ORDER_GET_ERROR,
                [
                    "error" => "Could not find any orders"
                ],
                null,
                Level::Error->value
            );
            return new Response(status: 400);
        }

        /** @var $order OrderEntity */
        $response = $this->paymentService->cancelPayment(
            $orderId,
            $order->getSalesChannelId(),
        );

        return new JsonApiResponse(json_encode($response));
    }
}

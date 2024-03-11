<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Subscriber;

use GuzzleHttp\Exception\GuzzleException;
use Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryEntity;
use Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryStates;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\StateMachine\Event\StateMachineStateChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vipps\Mobilepay\Service\PaymentService;

class OrderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityRepository              $orderDeliveryRepository,
        protected PaymentService                $paymentService,
        protected OrderTransactionStateHandler  $orderTransactionStateHandler,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'state_machine.order_delivery.state_changed' => 'onOrderDeliveryStateChange'
        ];
    }

    /**
     * @throws GuzzleException
     */
    public function onOrderDeliveryStateChange(StateMachineStateChangeEvent $event): void
    {
        $stateName = $event->getStateName();
        $stateTypeIsHandled = ($stateName === OrderDeliveryStates::STATE_SHIPPED ||
            $stateName === OrderDeliveryStates::STATE_CANCELLED ||
            $stateName === OrderDeliveryStates::STATE_RETURNED);
        if ($event->getTransitionSide() !== StateMachineStateChangeEvent::STATE_MACHINE_TRANSITION_SIDE_ENTER ||
            !$stateTypeIsHandled) {
            return;
        }

        $criteria = (new Criteria([$event->getTransition()->getEntityId()]))
            ->addAssociation('order')
            ->addAssociation('paymentMethod')
            ->setLimit(1);

        /** @var OrderDeliveryEntity $orderTransaction */
        $orderTransaction = $this->orderDeliveryRepository->search($criteria, $event->getContext())->first();

        /** @var OrderEntity $order */
        $order = $orderTransaction->getOrder();

        switch ($stateName) {
            case OrderDeliveryStates::STATE_SHIPPED:
                $this->paymentService->capturePayment(
                    $orderTransaction->getOrderId(),
                    $orderTransaction->getOrder()->getSalesChannelId(),
                    (int)($order->getAmountTotal() * 100),
                    null
                );
                break;
            case OrderDeliveryStates::STATE_RETURNED:
                $this->paymentService->refundPayment(
                    $orderTransaction->getOrderId(),
                    $orderTransaction->getOrder()->getSalesChannelId(),
                    (int)($order->getAmountTotal() * 100),
                    null
                );
                break;
            case OrderDeliveryStates::STATE_CANCELLED:
                $this->paymentService->cancelPayment(
                    $orderTransaction->getOrderId(),
                    $orderTransaction->getOrder()->getSalesChannelId()
                );
                break;
        }
    }
}

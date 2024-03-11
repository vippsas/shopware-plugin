<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Util;

use Monolog\Level;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class VippsMobilepayLogger
{
    final public const LOG_CHANNEL = 'vipps_mobilepay';
    public const ORDER_CAPTURE_SUCCESS = 'vippsMobilePay.order.capture.success';
    public const ORDER_REFUND_SUCCESS = 'vippsMobilePay.order.refund.success';
    public const ORDER_GET_SUCCESS = 'vippsMobilePay.order.get.success';
    public const ORDER_GET_ERROR = 'vippsMobilePay.order.get.error';
    public const ORDER_COMPLETE_SUCCESS = 'vippsMobilePay.order.finalize.success';
    public const ORDER_COMPLETE_CANCELLED = 'vippsMobilePay.order.finalize.cancelled';
    public const ORDER_COMPLETE_ERROR = 'vippsMobilePay.order.finalize.error';
    public const CREATE_PAYMENT_SUCCESS = 'vippsMobilePay.payment.create.success';
    public const IS_CONFIG_VALID_ERROR = 'vippsMobilePay.config.error';
    public const IS_CONFIG_VALID_SUCCESS = 'vippsMobilePay.config.success';
    public const ERROR = "vippsMobilePay.error";

    public function __construct(
        protected EntityRepository      $logEntryRepository,
        protected ConfigHelper          $configHelper
    ) {
    }

    public function logger(
        string $event,
        array  $context,
        ?string $salesChannelId = null,
        ?int   $level = Level::Error->value,
    ): void {
        $debug = $this->configHelper->getDebug($salesChannelId);
        if (!$debug && $level !== Level::Error->value) {
            return;
        }
        $this->logEntryRepository->create(
            [
                [
                    'message' => $event,
                    'context' => $context,
                    'level' => $level,
                    'channel' => self::LOG_CHANNEL
                ]
            ],
            Context::createDefaultContext()
        );
    }
}

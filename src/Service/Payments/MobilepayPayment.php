<?php

namespace Vipps\Mobilepay\Service\Payments;

use Shopware\Core\Checkout\Payment\Cart\AsyncPaymentTransactionStruct;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vipps\Mobilepay\Service\PaymentService;

class MobilepayPayment extends PaymentService
{
    final public const VIPPS_MOBILEPAY_NAME = 'MobilePay';
    final public const VIPPS_MOBILEPAY_DESCRIPTION = 'MobilePay from Vipps MobilePay';
    final public const VIPPS_MOBILEPAY_TECHNICALNAME = 'vippsmobilepay_mobilepay';
    final public const VIPPS_MOBILEPAY_MEDIA_FILE_NAME = 'mobilepay';

    public function pay(
        AsyncPaymentTransactionStruct $transaction,
        RequestDataBag $dataBag,
        SalesChannelContext $salesChannelContext
    ): RedirectResponse {
        return parent::pay($transaction, $dataBag, $salesChannelContext);
    }
}

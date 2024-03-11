<!-- START_METADATA
---
title: Adjust payments
sidebar_label: Adjust payments
sidebar_position: 10
pagination_next: null
pagination_prev: null
---
END_METADATA -->


# Adjust payments
 - [Order detail](#order-detail)
 - [Vipps MobilePay Status](#vipps-mobilepay-status)
   - [Actions](#actions)
 - [Delivery Status](#delivery-status)


## Order detail
When an order comes in from the creates it with [Vipps MobilePays API](https://developer.vippsmobilepay.com/api/epayment/#tag/QueryPayments/operation/getPayment)
where we check the state of the payment

| State                     | Shopware Payment status | Shopware Delivery status | Shopware Order status | Supported |
|:--------------------------|------------------------:|-------------------------:|----------------------:|:---------:|
| [CREATED](#created)       |                    Open |                     Open |                  Open |     ✅    |
| [ABORTED](#aborted)       |               Cancelled |                     Open |                  Open |     ✅    |
| [EXPIRED](#expired)       |                    Open |                     Open |                  Open |     ❌    |
| [AUTHORIZED](#authorized) |              Authorized |                     Open |                  Open |     ✅    |
| [TERMINATED](#terminated) |                    Open |                     Open |                  Open |     ❌    |

### Created
![order created](./images/adjust_payments/plugin_order_example_created.png)
### Aborted
![order aborted](./images/adjust_payments/plugin_order_example_aborted.png)
### Expired
*Not supported*
### Authorized
![order authorized](./images/adjust_payments/plugin_order_example_authorized.png)
### Terminated
*Not supported*

## Vipps MobilePay Status
Here is it possible to se the status of an order. It possible to see
- PSP Reference ID
- Reference ID
- Transaction Status
- Authorized Amount
- Captured Amount
- Refunded Amount
- Cancelled Amount
- Total Amount

![vipps mobilepay status](./images/adjust_payments/exampel_of_vipps_mobilepay_status.png)

### Actions
It's possible to perform actions  direct from Shopware administration on an order where there is paid with Vipps MobilePay

#### Capture
By default, if you click `Capture` it will capture the full authorized amount. it will change the Payment Status to `Paid`

#### Partial Capture
If you change the amount to capture and click `Capture` it will capture the amount you have put in. The max you can capture is the Authorized amount. It will change the Payment Status to `Partial paid`

#### Refund
If you want to refund the full amount of the captured amount then just click `Refund` and it will refund the full amount. It will change the Payment Status to `Refunded`

#### Partial Refund
If you want to refund a part of the captured amount then you just change the amount you want to and the click `Refund`. It will change the Payment Status to `Partial refunded`

#### Cancel
If you want to cancel an order make sure there is nothing captured or refunded and then you can just click `Cancel`. It will change the Payment status to `Cancelled`

## Delivery Status
We are listing to Delivery status

### Shipped
When an order change Delivery status to `Shipped` the full amount will be captured
![vipps mobilepay delivery status shipped](./images/adjust_payments/plugin_shipped.png)
![vipps mobilepay delivery vipps mobilepay shipped status](./images/adjust_payments/plugin_shipped_status.png)

### Returned
When an order change Delivery status to `Returned` the full amount will be refunded
![vipps mobilepay delivery status returned](./images/adjust_payments/plugin_returned.png)
![vipps mobilepay delivery vipps mobilepay returned status](./images/adjust_payments/plugin_returned_status.png)





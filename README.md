<!-- START_METADATA
---
title: "Vipps/MobilePay for Shopware plugin"
sidebar_position: 1
description: Provide Vipps and MobilePay payments for Shopware.
pagination_next: null
pagination_prev: null
---
END_METADATA -->

# Vipps/MobilePay plugin for Shopware

![Support and development by WEXO ](./docs/images/wexo.svg#gh-light-mode-only)![Support and development by WEXO](./docs/images/wexo_dark.svg#gh-dark-mode-only)

*This plugin is built and maintained by [WEXO](https://www.wexo.dk/) and is hosted on [GitHub](https://github.com/vippsas/shopware-plugin).*

<!-- START_COMMENT -->
💥 Please use the plugin pages on [https://developer.vippsmobilepay.com](https://developer.vippsmobilepay.com/docs/plugins-ext/shopware-plugin/). 💥
<!-- END_COMMENT -->

*Official Vipps/MobilePay payment plugin for Shopware.*

*Branded locally as MobilePay in Denmark and Finland, and as Vipps in Norway. One platform gathering more than 11 million users and more than 400,000 merchants across the Nordics. Give your users an easy, fast and familiar shopping experience.*

<!-- START_COMMENT -->
## Table of contents

- [Description](#description)
- [Single Payments](#single-payments)
- [How to get started](#how-to-get-started)
- [Installation](#installation)
- [How to get account keys from the merchant portal](#how-to-get-account-keys-from-merchant-portal)
  - [In which countries can I use Vipps MobilePay?](#in-which-countries-can-i-use-vipps-mobilepay)
  - [In which countries can I use MobilePay?](#in-which-countries-can-i-use-mobilepay)
  - [For how long is an order reserved?](#for-how-long-is-an-order-reserved)
- [How can I get help if I have any issues?](#how-can-i-get-help-if-i-have-any-issues)
  - [General FAQs](#general-faqs)
- [Requirements](#requirements)
- [Documentation](#documentation)
  - [General documentation](#general-documentation)
  - [Plugin documentation](#plugin-documentation)
- [Changelog](#changelog)
<!-- END_COMMENT -->

## Description

🌟 Integrated Vipps and MobilePay: With the Payment plugin, you can seamlessly offer Vipps and MobilePay as payment options, making transactions smoother for your customers.

📱 Automatic Phone Number Transfer: No more manual entries. When customers provide their phone numbers, they're smoothly transferred to their Vipps or MobilePay app, streamlining the checkout process.

🔄 Effortless Status Updates: Stay informed effortlessly. Our system ensures that order statuses are updated automatically, from payment authorization to order fulfillment.

💳 Flexible Payment Management: Take control of transactions with ease. Whether it's capturing the full order amount or issuing refunds, our system empowers you to manage payments flexibly.

📊 Live Order Tracking: Keep tabs on your orders in real-time. With a dedicated tab, you can track each order's status directly from Vipps and MobilePay.

## Single Payments

When you enable this plugin, your customers will be able to choose Vipps or MobilePay as a payment method directly in the checkout. There is no need to go via a third-party payment method. When choosing Vipps/MobilePay, user fills in name and address and is then asked to enter phone number in the Vipps/MobilePay landing page. User confirms the payment in the Vipps or MobilePay app.

## How to get started

- Sign up to use [*Payment Integration*](https://vippsmobilepay.com/online/payment-integration).
- After 1-2 days, you will get an email with login details to the Merchant Portal, [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/), where you can get the API credentials.
- Download and configure.

For more details, see [Applying for services](https://developer.vippsmobilepay.com/docs/knowledge-base/applying-for-services/).

## Installation

- [Installation](./docs/configure.md)

## How to get account keys from Merchant Portal

1. Sign in to [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
2. In the *Developer* section, choose *Production Keys*. Here you can find the merchant serial number (6 figures).
3. Click on *Show keys* under the API keys column to see *Client ID*, *Client Secret* and *Vipps Subscription Key*.

See:

- [Logging in to the portal](https://developer.vippsmobilepay.com/docs/knowledge-base/portal/)
- [How to find the API keys](https://developer.vippsmobilepay.com/docs/knowledge-base/portal/#how-to-find-the-api-keys)

### In which countries can I use Vipps MobilePay?

#### ![Vipps icon](./docs/images/vipps.png) Vipps

    🇳🇴 Norway

#### ![MobilePay icon](./docs/images/mp.png) MobilePay

    🇩🇰 Denmark
    🇫🇮 Finland

You can only get paid by users who have Vipps or MobilePay. Vipps is available in Norway and MobilePay is available in Denmark and Finland.

To learn more, see
[Offering Vipps MobilePay across borders](https://developer.vippsmobilepay.com/docs/knowledge-base/across-borders/).

### In which countries can I use MobilePay?

You can only get paid by users who have MobilePay in Finland and Denmark.


### For how long is an order reserved?

When a payment is completed with Vipps MobilePay, the money will be reserved, but only transferred to the merchant when the order is set to *Complete* or the money is captured manually. For MobilePay, this reservation period is 14 days, so you will need to ship and fulfill orders before this; or to make an agreement with the customer to capture the money before this period is over. For Vipps, the period is 180 days. For payments made by credit card in Vipps/MobilePay Checkout, the period can again be as short as 14 days.

If the order only contains virtual and downloadable products, it's possible to set up a rule in Shopware where you can change the shipment status to *shipped which will capture the authorized amount. See how the Shopware rule builder works on the [Shopware rule builder page](https://docs.shopware.com/en/shopware-6-en/settings/rules)

## How can I get help if I have any issues?

For issues with your Vipps/MobilePay plugin for Shopware installation, [contact WEXO](https://www.wexo.dk/kontakt). For other issues, contact [Vipps MobilePay](https://developer.vippsmobilepay.com/docs/contact/).

### General FAQs

See the
[Knowledge base](https://developer.vippsmobilepay.com/docs/knowledge-base/)
for more help with Vipps MobilePay eCommerce.

## Requirements

- Shopware: ```~6.5.0```

## Documentation

### General documentation

- [GitHub Repository](https://github.com/vippsas/shopware-plugin)
- [Vipps MobilePay Developer Resources](https://developer.vippsmobilepay.com/)
- [Vipps MobilePay ePayment API](https://developer.vippsmobilepay.com/docs/APIs/epayment-api/)
- [Vipps MobilePay Knowledge base](https://developer.vippsmobilepay.com/docs/knowledge-base/)

### Plugin documentation

- [Vipps MobilePay ePayment enhanced logging](./docs/enhanced_logging.md)
- [Vipps MobilePay ePayment adjust payments](./docs/adjust_payments.md)

## Changelog

Read the [Changelog](CHANGELOG.md)

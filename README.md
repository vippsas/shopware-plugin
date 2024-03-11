<!-- START_METADATA
---
title: "Vipps/MobilePay for Shopware plugin"
sidebar_position: 1
description: Provide Vipps and MobilePay payments for Shopware.
pagination_next: null
pagination_prev: null
draft: true
---
END_METADATA -->

# Vipps/MobilePay plugin for Shopware

![Support and development by Wexo ](./docs/images/wexo.svg#gh-light-mode-only)![Support and development by Wexo](./docs/images/wexo_dark.svg#gh-dark-mode-only)

![Vipps](./docs/images/vipps.png) *Available for Vipps.*

![MobilePay](./docs/images/mp.png) *Available for MobilePay.*


*This plugin is built and maintained by [Wexo](https://www.wexo.dk/) and can be downloaded from ______________.*

<!-- START_COMMENT -->
💥 Please use the plugin pages on [https://developer.vippsmobilepay.com](https://developer.vippsmobilepay.com/docs/plugins-ext/shopware-plugin/). 💥
<!-- END_COMMENT -->

*Official Vipps/MobilePay payment plugin for Shopware.*

*Branded locally as MobilePay in Denmark and Finland, and as Vipps in Norway. One platform gathering more than 11 million users and more than 400,000 merchants across the Nordics. Give your users an easy, fast and familiar shopping experience.*

## Description

## Single Payments

When you enable this plugin, your customers will be able to choose Vipps or MobilePay as a payment method directly in the checkout. There is no need to go via a third-party payment method. When choosing Vipps/MobilePay, user fills in name and address and is then asked to enter phone number in the Vipps/MobilePay landing page. User confirms the payment in the Vipps or MobilePay app.

## How to get started

- Sign up to use [*Payment Integration*](https://vippsmobilepay.com/online/payment-integration).
- After 1-2 days, you will get an email with login details to the Merchant Portal, [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/), where you can get the API credentials.
- Download and configure.

For more details, see [Applying for services](https://developer.vippsmobilepay.com/docs/knowledge-base/applying-for-services/).

## How to get account keys from the merchant portal

1. Sign in to [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
2. In the *Developer* section, choose *Production Keys*. Here, you can find the merchant serial number (6 figures).
3. Click on *Show keys* under the *API keys* column to see *Client ID*, *Client Secret*, and *Vipps Subscription Key*.

See [How to find the API keys](https://developer.vippsmobilepay.com/docs/developer-resources/portal#how-to-find-the-api-keys).

## Installation
- [Installation](./docs/configure.md)

## How to get account keys from Merchant Portal

1. Sign in to [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
2. In the *Developer* section, choose *Production Keys*. Here you can find the merchant serial number (6 figures).
3. Click on *Show keys* under the API keys column to see *Client ID*, *Client Secret* and *Vipps Subscription Key*.

See:

* [Logging in to the portal](https://developer.vippsmobilepay.com/docs/developer-resources/portal)
* [How to find the API keys](https://developer.vippsmobilepay.com/docs/developer-resources/portal#how-to-find-the-api-keys)

### In which countries can I use Vipps MobilePay?
#### ![Vipps](./docs/images/vipps.png) Vipps
    🇳🇴 Norway
#### ![MobilePay](./docs/images/mp.png) MobilePay
    🇩🇰 Denmark
    🇫🇮 Finland

You can only get paid by users who have Vipps or MobilePay. At the moment Vipps is only available in Norway and MobilePay is available in Denmark and Finland.

### In which countries can I use MobilePay?

You can only get paid by users who have MobilePay. At the moment plugin is only supporting MobilePay users in Finland. Support for MobilePay in Denmark coming later in Q1 2024.

### For how long is an order reserved?

When a payment is completed with Vipps MobilePay, the money will be reserved, but only transferred to the merchant when the order is set to “Complete” or the money is captured manually. For MobilePay, this reservation period is 14 days, so you will need to ship and fulfill orders before this; or to make an agreement with the customer to capture the money before this period is over. For Vipps, the period is 180 days. For payments made by credit card in Vipps/MobilePay Checkout, the period can again be as short as 14 days.

If the order only contains virtual and downloadable products, the plugin will capture the order automatically and set the order to “Completed” as is the standard WooCommerce rule.

## How can I get help if I have any issues?

For issues with your Vipps/MobilePay plugin for Shopware installation, [contact Wexo](https://www.wexo.dk/kontakt). For other issues, contact [Vipps MobilePay](https://developer.vippsmobilepay.com/docs/contact/).


### General FAQs

See the
[Knowledge base](https://developer.vippsmobilepay.com/docs/knowledge-base/)
for more help with Vipps MobilePay eCommerce.

## Requirements
- Shopware: ```~6.5.0```

## Documentation
- [GitHub Repository](https://github.com/vippsas/shopware-plugin)
- [Vipps MobilePay Developer Resources](https://developer.vippsmobilepay.com/)
- [Vipps MobilePay ePayment API](https://developer.vippsmobilepay.com/docs/APIs/epayment-api/)
- [Vipps MobilePay Knowledge base](https://developer.vippsmobilepay.com/docs/knowledge-base/)

## Changelog
[Changelog](CHANGELOG.md)
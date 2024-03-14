<!-- START_METADATA
---
title: Install and configure Shopware
sidebar_label: Install and configure
sidebar_position: 10
description: How to install and configure the Shopware Payments plugin
pagination_next: null
pagination_prev: null
---
END_METADATA -->

# Install and configure

<!-- START_COMMENT -->
- [Installation](#installation)
  - [Composer](#composer)
  - [Git](#git)
  - [Shopware UI](#shopware-ui)
- [Configure](#configure)
  - [Configuration of plugin](#configuration-of-plugin)
  - [Configuration of payment method](#configuration-of-payment-method)
  - [Configuration of the sales channel](#configuration-of-the-sales-channel)
<!-- END_COMMENT -->

## Installation

### Composer

```bash
Coming soon
```

### GIT

Start by navigating into `/var/www/<project-name>/custom/plugins/` then clone the repository with `SSH` or `HTTPS`

```bash
git clone git@github.com:vippsas/shopware-plugin.git VippsMobilePayEPayment
```

### Composer & Git installment of plugin

Installing the plugin without using the Shopware UI and activating the plugin

```bash
php bin/console plugin:install --activate VippsMobilepayEpayment
```

After running the installation and activation of the plugin clear the cache.

```bash
php bin/console cache:clear
```

The plugin is now installed and activated go to [configure](#configure)

### Shopware UI

Download the latest [release](https://github.com/vippsas/shopware-plugin/releases/latest)

Go to *My extensions* and click *Upload extension*.

![Upload extensions](./images/installation/upload_extension.png)

Unzip the `*.zip` that you downloaded.

The plugin should now be available and should show up in the list.

![Plugin available](./images/installation/plugin_available.png)

Click *install* and wait for the page to refresh. When it's done click the active slider.

![Plugin activate](./images/installation/plugin_activate.png)

It should now be blue.

![Plugin install and activated](./images/installation/plugin_installed_and_activated.png)

The plugin is installed and activated now go to the [configure](#configure) section.

## Configure

### Configuration of plugin

Start by clicking the 3 dots and the click *Configure*.

![Configure plugin](./images/installation/plugin_configure.png)

Select the sales channel that matches the [requirements](#requirements-for-the-sales-channel).

#### Parameters

- API Endpoint - Vipps MobilePay API URL, choose "Test environment" or "Production environment".
- Vipps MobilePay MSN - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Client ID - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Client secret - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Primary subscription key - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Secondary subscription key - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).

![Plugin config example](./images/installation/plugin_temp_config.png)

**REMEMBER** only use "production environment" for production keys, otherwise use "test environment".

To test if the right credentials are used, press the *Test API Connection*.
If there is no error, then the credentials are correct.
If there comes an error, then double-check the credentials to see if they're correct.

### Configuration of payment method

Start by going to `https://<domain>/admin#/sw/settings/payment/overview` to see that the payments are correctly installed.

![Payment method installed](./images/installation/plugin_payment_method_installed.png)

### Configuration of the sales channel

Go to the sales channel where you want to add Vipps or MobilePay.
It's **important** that the sales channel is configured the right way. For example:

![Sales channels](./images/installation/exampel_of_saleschannel.png)

#### Requirements for the sales channel

| Merchant     | Countries    | Default Country | Languages | Default Languages | Currencies      | Default Currencies | Payment Methods                       |  Test environment  |  Production environment  |
|--------------|--------------|-----------------|-----------|-------------------|-----------------|--------------------|---------------------------------------|:------------------:|:------------------------:|
| Vipps        | 🇳🇴 Norway    | Norway          | Norsk     | Norsk             | Norwegian krone | Norwegian krone    | Vipps \| Vipps MobilePay \| ePayment  |         ✅          |            ✅             |
| MobilePay DA | 🇩🇰 Denmark   | Denmark         | Danish    | Danish            | Danish krone    | Danish krone       | MobilePay \| Vipps MobilePay \| ePayment |         ❌          |            ✅             |
| MobilePay FI | 🇫🇮 Finland   | Finland         | Suomi     | Suomi             | Euro            | Euro               | MobilePay \| Vipps MobilePay \| ePayment |         ❌          |            ✅             |

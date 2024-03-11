<!-- START_METADATA
---
title: Install and configure Shopware
sidebar_label: Install and configure
sidebar_position: 10
pagination_next: null
pagination_prev: null
---
END_METADATA -->

# Install and configure
- [Installation](#installation)
- [Configure](#configure)

## Installation
- [Git](#git)
- [Shopware UI](#shopware-ui)

### GIT
Start by navigating into `/var/www/<project-name>/custom/plugins/` then clone the repository with `SSH` or `HTTPS`

```bash 
git clone git@github.com:vippsas/shopware-plugin.git VippsMobilePayEPayment
```

Then run 
```bash 
php bin/console plugin:install --activate VippsMobilepayEpayment
```

### Shopware UI
Download lastest [release](https://github.com/vippsas/shopware-plugin/releases/latest) 

Go to My extensions and click Upload extension
![upload extensions](./images/installation/upload_extension.png)

Upload the `*.zip` that you downloaded.

The plugin should now be available and shows up in the list 
![plugin available](./images/installation/plugin_available.png)

Click install and wait for the page to refresh when its done click the active slider
![plugin activate](./images/installation/plugin_activate.png)

And i should now be blue
![plugin install and activated](./images/installation/plugin_installed_and_activated.png)

The plugin is installed and activated now

## Configure

- [Configuration of plugin](#configuration-of-plugin)
- [Configuration of payment method](#configuration-of-payment-method)
- [Configuration of SalesChannel](#configuration-of-saleschannel)

### Configuration of plugin
Start by clicking the 3 dots and the click "Configure" 
![configure plugin](./images/installation/plugin_configure.png)

Start by selection the saleschannel that match the [requirements](#requirements)

#### Parameters
- API Endpoint - Vipps MobilePay API url choose "Test enviroment" or "Production enviroment".
- Vipps MobilePay MSN - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Client id - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Client secret - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Primary subscription key - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).
- Vipps MobilePay Secondary subscription key - can be obtained through [portal.vippsmobilepay.com](https://portal.vippsmobilepay.com/).

![plugin config exampel](./images/installation/plugin_temp_config.png)

**REMEBER** only use "production environment" for production keys else use "test environment". 

To test if the right credentials press the "Test API Connection" if there is no error then the right credentials entered. if there comes an error then double check the credentials to see if they are correct.

### Configuration of payment method
Start by going to `https://<domain>/admin#/sw/settings/payment/overview` and see the payments are correct installed
![payment method installed](./images/installation/plugin_payment_method_installed.png)


### Configuration of SalesChannel
Go to the SalesChannel where you want to add Vipps or

#### Requirements

| Merchant      |  Countries  | Default country  |  Languages   |  Default Languages  |
|---            |---          |---|---|---|
| Vipps         |     🇳🇴 Norway        | Norway  | Norsk  | Norsk  |
| MobilePay DA  |     🇩🇰 Denmark        | Denmark  | Danish  | Danish  |
| MobilePay FI  |     🇫🇮 Finland      | Finland  | Suomi  | Suomi  |

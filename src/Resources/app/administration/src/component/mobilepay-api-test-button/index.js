const { Component, Mixin } = Shopware;
import template from './mobilepay-api-test-button.html.twig';

Component.register('mobilepay-api-test-button', {
    template: template,

    props: ['btnLabel'],
    inject: ['mobilepayApiService'],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,
        };
    },

    computed: {
        pluginConfig() {
            let systemConfigComponent = this.$parent;
            while (!systemConfigComponent.hasOwnProperty('actualConfigData')) {
                systemConfigComponent = systemConfigComponent.$parent
            }
            let selectedSalesChannelId = systemConfigComponent.currentSalesChannelId;
            let config = systemConfigComponent.actualConfigData;
            // Properties NOT set in the sales channel config will be inherited from default config.
            return Object.assign({}, config.null, config[selectedSalesChannelId]);
        }
    },

    methods: {
        saveFinish() {
            this.isSaveSuccessful = false;
        },

        testApi() {
            this.isLoading = true;

            this.mobilepayApiService
                .testConfig(this.pluginConfig)
                .then((responseCode) => {
                    if (responseCode === 200) {
                        this.isSaveSuccessful = true;
                        this.createNotificationSuccess({
                            title: this.$tc('mobilepay-api-test-button.title'),
                            message: this.$tc('mobilepay-api-test-button.success')
                        });
                    } else {
                        this.createNotificationError({
                            title: this.$tc('mobilepay-api-test-button.title'),
                            message: this.$tc('mobilepay-api-test-button.error')
                        });
                    }

                    this.isLoading = false;
                })
                .catch((error) => {
                    this.createNotificationError({
                        title: this.$tc('mobilepay-api-test-button.title'),
                        message: this.$tc('mobilepay-api-test-button.error')
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    }
})

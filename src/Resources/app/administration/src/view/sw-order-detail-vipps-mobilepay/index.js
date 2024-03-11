import template from './sw-order-detail-vipps-mobilepay.html.twig';
import './sw-oder-detail-vipps-mobilepay.scss';

const { mapGetters, mapState } = Shopware.Component.getComponentHelper();
const { State } = Shopware;

Shopware.Component.register('sw-order-detail-vipps-mobilepay', {
    template,

    inject: ['vippsMobilePayService'],

    computed: {
        ...mapGetters('swOrderDetail', [
            'isLoading',
        ]),
        ...mapState('swOrderDetail', [
            'order',
            'versionContext',
            'orderAddressIds',
        ]),
    },

    metaInfo() {
        return {
            title: 'Vipps MobilePay'
        }
    },

    data() {
        return {
            transaction: null,
            captureAvailable: null,
            captureAmount: null,
            refundAvailable: null,
            refundAmount: null,
            min: 0,
        }
    },

    methods: {
        createdComponent() {
            State.commit('swOrderDetail/setLoading', ['order', true]);
            this.vippsMobilePayService.getPayments(this.order.id).then(response => {
                this.transaction = response;
                this.captureAvailable = (response.aggregate.authorizedAmount.value - response.aggregate.capturedAmount.value) / 100;
                this.captureAmount = this.captureAvailable;
                this.refundAvailable = (response.aggregate.capturedAmount.value - response.aggregate.refundedAmount.value) / 100
                this.refundAmount = this.refundAvailable
            }).finally(() => {
                State.commit('swOrderDetail/setLoading', ['order', false]);
            });
        },

        updateCaptureAmount(value) {
            this.captureAmount = value;
        },

        updateRefundAmount(value) {
            this.refundAmount = value;
        },

        capture() {
            State.commit('swOrderDetail/setLoading', ['order', true]);
            this.vippsMobilePayService.capture(
                this.order.id,
                this.captureAmount * 100,
                this.transaction.aggregate.authorizedAmount.currency
            ).then(response => {
                this.transaction = response
                this.$emit('save-edits');
            }).finally(() => {
                State.commit('swOrderDetail/setLoading', ['order', false]);
                location.reload();
            });
        },

        refund() {
            State.commit('swOrderDetail/setLoading', ['order', true]);
            this.vippsMobilePayService.refund(
                this.order.id,
                this.refundAmount * 100,
                this.transaction.aggregate.authorizedAmount.currency
            ).then(response => {
                this.transaction = response
                this.$emit('save-edits');
            }).finally(() => {
                State.commit('swOrderDetail/setLoading', ['order', false]);
                location.reload();
            });
        },
        cancel() {
            State.commit('swOrderDetail/setLoading', ['order', true]);
            this.vippsMobilePayService.cancel(this.order.id).then(response => {
                this.transaction = response
                this.$emit('save-edits');
            }).finally(() => {
                State.commit('swOrderDetail/setLoading', ['order', false]);
                location.reload();
            });
        },
        formatCurrency(value, currency) {
            const adminLocaleLanguage = Shopware.State.getters.adminLocaleLanguage;
            const adminLocaleRegion = Shopware.State.getters.adminLocaleRegion;
            const locals = adminLocaleLanguage + "-" + adminLocaleRegion;
            return (value / 100).toLocaleString(locals, {
                style: 'currency',
                currency: currency
            });
        }
    },

    created() {
        this.createdComponent();
    }
})

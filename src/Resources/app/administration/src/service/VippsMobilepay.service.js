const ApiService = Shopware.Classes.ApiService;
const { Application } = Shopware;

class vippsMobilePayService extends ApiService {
    getPayments(orderId) {
        return this.httpClient.get(
            `/${this.getApiBasePath()}/payments?orderId=${orderId}`,
            {
                headers: this.getBasicHeaders()
            }
        ).then(response => ApiService.handleResponse(response));
    };

    capture(orderId, amount, currency) {
        return this.httpClient.post(
            `/${this.getApiBasePath()}/capture`,
            {
                orderId,
                amount,
                currency
            },
            {
                headers: this.getBasicHeaders()
            }
        ).then(response => ApiService.handleResponse(response));
    };

    refund(orderId, amount, currency) {
        return this.httpClient.post(
            `/${this.getApiBasePath()}/refund`,
            {
                orderId,
                amount,
                currency
            },
            {
                headers: this.getBasicHeaders()
            }
        ).then(response => ApiService.handleResponse(response));
    };

    cancel(orderId) {
        return this.httpClient.post(
            `/${this.getApiBasePath()}/cancel`,
            {
                orderId
            },
            {
                headers: this.getBasicHeaders()
            }
        ).then(response => ApiService.handleResponse(response));
    };
}

Application.addServiceProvider('vippsMobilePayService', (container) => {
    const initContainer = Application.getContainer('init');
    return new vippsMobilePayService(initContainer.httpClient, container.loginService, 'vipps');
});
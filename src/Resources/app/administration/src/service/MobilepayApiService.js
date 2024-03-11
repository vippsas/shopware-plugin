const ApiService = Shopware.Classes.ApiService;
const { Application } = Shopware;

class MobilepayApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'mobilepay-api') {
        super(httpClient, loginService, apiEndpoint);
    }

    testConfig(data) {
        const headers = this.getBasicHeaders({});
        return this.httpClient
            .post(
                `_action/${this.getApiBasePath()}/verify`,
                data,
                headers
            )
            .then((response) => {
                return ApiService.handleResponse(response.status);
            });
    }
}

Application.addServiceProvider('mobilepayApiService', (container) => {
    const initContainer = Application.getContainer('init');
    return new MobilepayApiService(initContainer.httpClient, container.loginService);
});

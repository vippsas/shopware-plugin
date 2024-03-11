import './service/MobilepayApiService';
import './component/mobilepay-api-test-button';
import './component/vipps-mobilepay-info-box';
import './view/sw-order-detail-vipps-mobilepay';
import './service/VippsMobilepay.service';
import './page/sw-order-detail';

import localeDE from './snippet/de_DE.json';
import localeEN from './snippet/en_GB.json';
import localeDK from './snippet/da_DK.json';
import localeFI from './snippet/fi_FI.json';
import localeNO from './snippet/nb_NO.json';
import localeSE from './snippet/sv_SE.json';

Shopware.Locale.extend('de-DE', localeDE);
Shopware.Locale.extend('en-GB', localeEN);
Shopware.Locale.extend('da-DK', localeDK);
Shopware.Locale.extend('fi-FI', localeFI);
Shopware.Locale.extend('nb-NO', localeNO);
Shopware.Locale.extend('sv-SE', localeSE);

Shopware.Module.register('vipps-mobilepay', {
    routeMiddleware(next, currentRoute) {
        if (currentRoute.name === 'sw.order.detail') {
            currentRoute.children.push({
                name: 'vipps.mobilepay',
                path: '/sw/order/detail/:id/vipps-mobilepay',
                component: 'sw-order-detail-vipps-mobilepay',
                meta: {
                    parentPath: 'sw.order.index'
                }
            });
        }
        next(currentRoute);
    }
});


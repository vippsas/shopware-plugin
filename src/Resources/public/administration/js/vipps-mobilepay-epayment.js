!function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(r,i,function(t){return e[t]}.bind(null,i));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p=(window.__sw__.assetPath + '/bundles/vippsmobilepayepayment/'),n(n.s="fO9/")}({"05Cb":function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){for(var r=0;r<t.length;r++){var i=t[r];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,(o=i.key,a=void 0,a=function(e,t){if("object"!==n(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var i=r.call(e,t||"default");if("object"!==n(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(o,"string"),"symbol"===n(a)?a:String(a)),i)}var o,a}function o(e,t){return(o=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e})(e,t)}function a(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=u(e);if(t){var i=u(this).constructor;n=Reflect.construct(r,arguments,i)}else n=r.apply(this,arguments);return s(this,n)}}function s(e,t){if(t&&("object"===n(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function u(e){return(u=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var c=Shopware.Classes.ApiService,l=Shopware.Application,p=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&o(e,t)}(l,e);var t,n,s,u=a(l);function l(){return r(this,l),u.apply(this,arguments)}return t=l,(n=[{key:"getPayments",value:function(e){return this.httpClient.get("/".concat(this.getApiBasePath(),"/payments?orderId=").concat(e),{headers:this.getBasicHeaders()}).then((function(e){return c.handleResponse(e)}))}},{key:"capture",value:function(e,t,n){return this.httpClient.post("/".concat(this.getApiBasePath(),"/capture"),{orderId:e,amount:t,currency:n},{headers:this.getBasicHeaders()}).then((function(e){return c.handleResponse(e)}))}},{key:"refund",value:function(e,t,n){return this.httpClient.post("/".concat(this.getApiBasePath(),"/refund"),{orderId:e,amount:t,currency:n},{headers:this.getBasicHeaders()}).then((function(e){return c.handleResponse(e)}))}},{key:"cancel",value:function(e){return this.httpClient.post("/".concat(this.getApiBasePath(),"/cancel"),{orderId:e},{headers:this.getBasicHeaders()}).then((function(e){return c.handleResponse(e)}))}}])&&i(t.prototype,n),s&&i(t,s),Object.defineProperty(t,"prototype",{writable:!1}),l}(c);l.addServiceProvider("vippsMobilePayService",(function(e){var t=l.getContainer("init");return new p(t.httpClient,e.loginService,"vipps")}))},ENJT:function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){for(var r=0;r<t.length;r++){var i=t[r];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,(o=i.key,a=void 0,a=function(e,t){if("object"!==n(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var i=r.call(e,t||"default");if("object"!==n(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(o,"string"),"symbol"===n(a)?a:String(a)),i)}var o,a}function o(e,t){return(o=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e})(e,t)}function a(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=u(e);if(t){var i=u(this).constructor;n=Reflect.construct(r,arguments,i)}else n=r.apply(this,arguments);return s(this,n)}}function s(e,t){if(t&&("object"===n(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function u(e){return(u=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var c=Shopware.Classes.ApiService,l=Shopware.Application,p=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&o(e,t)}(l,e);var t,n,s,u=a(l);function l(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"mobilepay-api";return r(this,l),u.call(this,e,t,n)}return t=l,(n=[{key:"testConfig",value:function(e){var t=this.getBasicHeaders({});return this.httpClient.post("_action/".concat(this.getApiBasePath(),"/verify"),e,t).then((function(e){return c.handleResponse(e.status)}))}}])&&i(t.prototype,n),s&&i(t,s),Object.defineProperty(t,"prototype",{writable:!1}),l}(c);l.addServiceProvider("mobilepayApiService",(function(e){var t=l.getContainer("init");return new p(t.httpClient,e.loginService)}))},P8hj:function(e,t,n){"use strict";function r(e,t){for(var n=[],r={},i=0;i<t.length;i++){var o=t[i],a=o[0],s={id:e+":"+i,css:o[1],media:o[2],sourceMap:o[3]};r[a]?r[a].parts.push(s):n.push(r[a]={id:a,parts:[s]})}return n}n.r(t),n.d(t,"default",(function(){return b}));var i="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!i)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o={},a=i&&(document.head||document.getElementsByTagName("head")[0]),s=null,u=0,c=!1,l=function(){},p=null,d="data-vue-ssr-id",f="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function b(e,t,n,i){c=n,p=i||{};var a=r(e,t);return v(a),function(t){for(var n=[],i=0;i<a.length;i++){var s=a[i];(u=o[s.id]).refs--,n.push(u)}t?v(a=r(e,t)):a=[];for(i=0;i<n.length;i++){var u;if(0===(u=n[i]).refs){for(var c=0;c<u.parts.length;c++)u.parts[c]();delete o[u.id]}}}}function v(e){for(var t=0;t<e.length;t++){var n=e[t],r=o[n.id];if(r){r.refs++;for(var i=0;i<r.parts.length;i++)r.parts[i](n.parts[i]);for(;i<n.parts.length;i++)r.parts.push(y(n.parts[i]));r.parts.length>n.parts.length&&(r.parts.length=n.parts.length)}else{var a=[];for(i=0;i<n.parts.length;i++)a.push(y(n.parts[i]));o[n.id]={id:n.id,refs:1,parts:a}}}}function m(){var e=document.createElement("style");return e.type="text/css",a.appendChild(e),e}function y(e){var t,n,r=document.querySelector("style["+d+'~="'+e.id+'"]');if(r){if(c)return l;r.parentNode.removeChild(r)}if(f){var i=u++;r=s||(s=m()),t=w.bind(null,r,i,!1),n=w.bind(null,r,i,!0)}else r=m(),t=S.bind(null,r),n=function(){r.parentNode.removeChild(r)};return t(e),function(r){if(r){if(r.css===e.css&&r.media===e.media&&r.sourceMap===e.sourceMap)return;t(e=r)}else n()}}var h,g=(h=[],function(e,t){return h[e]=t,h.filter(Boolean).join("\n")});function w(e,t,n,r){var i=n?"":r.css;if(e.styleSheet)e.styleSheet.cssText=g(t,i);else{var o=document.createTextNode(i),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(o,a[t]):e.appendChild(o)}}function S(e,t){var n=t.css,r=t.media,i=t.sourceMap;if(r&&e.setAttribute("media",r),p.ssrId&&e.setAttribute(d,t.id),i&&(n+="\n/*# sourceURL="+i.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(i))))+" */"),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}},QUzy:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API Test","success":"Verbindung wurde erfolgreich getestet","error":"Verbindung konnte nicht hergestellt werden. Bitte prüfe die Zugangsdaten"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Einfangen","refund":"Rückerstattung","cancel":"Annullieren","info-box":{"title":"Notiz:","information":"Reservierungen mit MobilePay werden nach 7 Tagen storniert. Denken Sie daran, Ihre Bestellungen zu versenden und auszuführen."}}}')},S1y5:function(e,t,n){var r=n("s15O");r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);(0,n("P8hj").default)("3c7d4460",r,!0,{})},biMm:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API Test","success":"Connection tested with success","error":"Connection could not be established. Please check the access data"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Capture","partialCapture":"Partial Capture","maxCapture":"Capture MAX","refund":"Refund","cancel":"Cancel","info-box":{"title":"Note:","information":"Reservations with MobilePay will be cancelled after 7 days. Remember to ship and fulfill your orders."}}}')},bjK9:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API Test","success":"Forbindelses test var en success","error":"Forbindelse kunne ikke oprettes. Check konfigurations data"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Hæv","refund":"Refunder","cancel":"Annuller","info-box":{"title":"Note:","information":"Reservationer med MobilePay vil blive annulleret efter 7 dage. Husk at få sendt og fuldført dine ordre."}}}')},eUTC:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API-testi","success":"Yhteys testattu onnistuneesti","error":"Yhteyttä ei voitu muodostaa. Tarkista pääsytiedot"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Vangitse","partialCapture":"Osittainen vanginta","maxCapture":"Vangitse MAX","refund":"Palauta","cancel":"Peruuta","info-box":{"title":"Huomaa:","information":"Varaukset MobilePayn kanssa peruutetaan 7 päivän kuluttua. Muista lähettää ja täyttää tilauksesi."}}}')},"fO9/":function(e,t,n){"use strict";n.r(t);n("ENJT");var r=Shopware,i=r.Component,o=r.Mixin;i.register("mobilepay-api-test-button",{template:'<div>\n    <sw-button-process\n            :isLoading="isLoading"\n            :processSuccess="isSaveSuccessful"\n            variant="primary"\n            @process-finish="saveFinish"\n            @click="testApi"\n    >{{ btnLabel }}</sw-button-process>\n</div>',props:["btnLabel"],inject:["mobilepayApiService"],mixins:[o.getByName("notification")],data:function(){return{isLoading:!1,isSaveSuccessful:!1}},computed:{pluginConfig:function(){for(var e=this.$parent;!e.hasOwnProperty("actualConfigData");)e=e.$parent;var t=e.currentSalesChannelId,n=e.actualConfigData;return Object.assign({},n.null,n[t])}},methods:{saveFinish:function(){this.isSaveSuccessful=!1},testApi:function(){var e=this;this.isLoading=!0,this.mobilepayApiService.testConfig(this.pluginConfig).then((function(t){200===t?(e.isSaveSuccessful=!0,e.createNotificationSuccess({title:e.$tc("mobilepay-api-test-button.title"),message:e.$tc("mobilepay-api-test-button.success")})):e.createNotificationError({title:e.$tc("mobilepay-api-test-button.title"),message:e.$tc("mobilepay-api-test-button.error")}),e.isLoading=!1})).catch((function(t){e.createNotificationError({title:e.$tc("mobilepay-api-test-button.title"),message:e.$tc("mobilepay-api-test-button.error")})})).finally((function(){e.isLoading=!1}))}}});n("ttHz");Shopware.Component.register("vipps-mobilepay-info-box",{template:'{% block vipps_mobilepay_info_box %}\n    <div class="info-box-container">\n        <div class="content">\n            <div class="info-box-title">\n                {{ $tc(\'vipps-mobilepay.info-box.title\') }}\n            </div>\n            <div class="info-box">\n                {{ $tc(\'vipps-mobilepay.info-box.information\') }}\n            </div>\n        </div>\n    </div>\n{% endblock %}'});n("S1y5");function a(e){return(a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function u(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?s(Object(n),!0).forEach((function(t){c(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function c(e,t,n){return(t=function(e){var t=function(e,t){if("object"!==a(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var r=n.call(e,t||"default");if("object"!==a(r))return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"===a(t)?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var l=Shopware.Component.getComponentHelper(),p=l.mapGetters,d=l.mapState,f=Shopware.State;Shopware.Component.register("sw-order-detail-vipps-mobilepay",{template:'<sw-card title="Vipps MobilePay Status" :isLoading="isLoading" positionIdentifier="vippsMobilePay">\n    <sw-card-section v-if="transaction">\n        <sw-container columns="1fr" gap="5px">\n            <div class="lineInfo">\n                <div>PSP Reference ID:</div>\n                <div>{{ transaction.pspReference }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Reference ID:</div>\n                <div>{{ transaction.reference }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Transaction Status:</div>\n                <div>{{ transaction.state }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Authorized Amount:</div>\n                <div>{{ formatCurrency(transaction.aggregate.authorizedAmount.value, transaction.aggregate.authorizedAmount.currency) }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Captured Amount:</div>\n                <div>{{ formatCurrency(transaction.aggregate.capturedAmount.value, transaction.aggregate.capturedAmount.currency) }} </div>\n            </div>\n            <div class="lineInfo">\n                <div>Refunded Amount:</div>\n                <div>{{ formatCurrency(transaction.aggregate.refundedAmount.value, transaction.aggregate.refundedAmount.currency) }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Cancelled Amount:</div>\n                <div>{{ formatCurrency(transaction.aggregate.cancelledAmount.value, transaction.aggregate.cancelledAmount.currency) }}</div>\n            </div>\n            <div class="lineInfo">\n                <div>Total Amount:</div>\n                <div>{{ formatCurrency(transaction.amount.value, transaction.amount.currency) }}</div>\n            </div>\n        </sw-container>\n    </sw-card-section>\n    <sw-card-section v-if="transaction">\n        <sw-container columns="1fr 1fr 1fr" gap="10px">\n            <div class="btn-input-container">\n                <sw-button\n                        :disabled="isLoading || ![\'open\', \'in_progress\', \'authorized\'].includes(order.transactions[0].stateMachineState.technicalName) && captureAvailable <= 0"\n                        @click="capture"\n                >\n                    {{ $tc("vipps-mobilepay.capture") }}\n                </sw-button>\n                    <sw-field\n                            type="number"\n                            v-model="captureAmount"\n                            :min="min"\n                            :max="captureAvailable"\n                            input-change="updateCaptureAmount()"\n                    >\n                    </sw-field>\n            </div>\n            <div class="btn-input-container">\n                <sw-button\n                        :disabled="isLoading || ![\'paid\', \'paid_partially\'].includes(order.transactions[0].stateMachineState.technicalName) && refundAvailable <= 0"\n                        @click="refund"\n                >\n                    {{ $tc("vipps-mobilepay.refund") }}\n                </sw-button>\n                <sw-field\n                        type="number"\n                        v-model="refundAmount"\n                        :min="min"\n                        :max="refundAvailable"\n                        input-change="updateRefundAmount()"\n                >\n                </sw-field>\n            </div>\n\n            <sw-button\n                    :disabled="isLoading || ![\'open\', \'in_progress\', \'authorized\'].includes(order.transactions[0].stateMachineState.technicalName)"\n                    @click="cancel"\n            >\n                {{ $tc("vipps-mobilepay.cancel") }}\n            </sw-button>\n        </sw-container>\n    </sw-card-section>\n</sw-card>\n',inject:["vippsMobilePayService"],computed:u(u({},p("swOrderDetail",["isLoading"])),d("swOrderDetail",["order","versionContext","orderAddressIds"])),metaInfo:function(){return{title:"Vipps MobilePay"}},data:function(){return{transaction:null,captureAvailable:null,captureAmount:null,refundAvailable:null,refundAmount:null,min:0}},methods:{createdComponent:function(){var e=this;f.commit("swOrderDetail/setLoading",["order",!0]),this.vippsMobilePayService.getPayments(this.order.id).then((function(t){e.transaction=t,e.captureAvailable=(t.aggregate.authorizedAmount.value-t.aggregate.capturedAmount.value)/100,e.captureAmount=e.captureAvailable,e.refundAvailable=(t.aggregate.capturedAmount.value-t.aggregate.refundedAmount.value)/100,e.refundAmount=e.refundAvailable})).finally((function(){f.commit("swOrderDetail/setLoading",["order",!1])}))},updateCaptureAmount:function(e){this.captureAmount=e},updateRefundAmount:function(e){this.refundAmount=e},capture:function(){var e=this;f.commit("swOrderDetail/setLoading",["order",!0]),this.vippsMobilePayService.capture(this.order.id,100*this.captureAmount,this.transaction.aggregate.authorizedAmount.currency).then((function(t){e.transaction=t,e.$emit("save-edits")})).finally((function(){f.commit("swOrderDetail/setLoading",["order",!1]),location.reload()}))},refund:function(){var e=this;f.commit("swOrderDetail/setLoading",["order",!0]),this.vippsMobilePayService.refund(this.order.id,100*this.refundAmount,this.transaction.aggregate.authorizedAmount.currency).then((function(t){e.transaction=t,e.$emit("save-edits")})).finally((function(){f.commit("swOrderDetail/setLoading",["order",!1]),location.reload()}))},cancel:function(){var e=this;f.commit("swOrderDetail/setLoading",["order",!0]),this.vippsMobilePayService.cancel(this.order.id).then((function(t){e.transaction=t,e.$emit("save-edits")})).finally((function(){f.commit("swOrderDetail/setLoading",["order",!1]),location.reload()}))},formatCurrency:function(e,t){var n=Shopware.State.getters.adminLocaleLanguage,r=Shopware.State.getters.adminLocaleRegion;return(e/100).toLocaleString(n+"-"+r,{style:"currency",currency:t})}},created:function(){this.createdComponent()}});n("05Cb");Shopware.Component.override("sw-order-detail",{template:'{% block sw_order_detail_content_tabs_extension %}\n    <sw-tabs-item\n            v-if="!isOrderEditing"\n            class="sw-order-detail__tabs-tab-vippsMobilePay"\n            :route="{ name: \'vipps.mobilepay\', params: { id: $route.params.id } }"\n            :title="$tc(\'vipps-mobilepay.tabDetails\')"\n    >\n        {{ $tc(\'vipps-mobilepay.tabDetails\') }}\n    </sw-tabs-item>\n{% endblock %}\n'});var b=n("QUzy"),v=n("biMm"),m=n("bjK9"),y=n("eUTC"),h=n("ifjV"),g=n("pF3c");Shopware.Locale.extend("de-DE",b),Shopware.Locale.extend("en-GB",v),Shopware.Locale.extend("da-DK",m),Shopware.Locale.extend("fi-FI",y),Shopware.Locale.extend("nb-NO",h),Shopware.Locale.extend("sv-SE",g),Shopware.Module.register("vipps-mobilepay",{routeMiddleware:function(e,t){"sw.order.detail"===t.name&&t.children.push({name:"vipps.mobilepay",path:"/sw/order/detail/:id/vipps-mobilepay",component:"sw-order-detail-vipps-mobilepay",meta:{parentPath:"sw.order.index"}}),e(t)}})},ifjV:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API Test","success":"Tilkobling testet med suksess","error":"Tilkoblingen kunne ikke opprettes. Vennligst sjekk tilgangsdataene"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Fang","partialCapture":"Delvis fangst","maxCapture":"Fangst MAX","refund":"Refusjon","cancel":"Avbryt","info-box":{"title":"Merk:","information":"Reservasjoner med MobilePay blir kansellert etter 7 dager. Husk å sende og fullføre bestillingene dine."}}}')},pF3c:function(e){e.exports=JSON.parse('{"mobilepay-api-test-button":{"title":"API Test","success":"Anslutningen testades med framgång","error":"Det gick inte att upprätta anslutning. Kontrollera åtkomstdata"},"vipps-mobilepay":{"tabDetails":"Vipps MobilePay","capture":"Fånga","partialCapture":"Delvis fångst","maxCapture":"Fånga MAX","refund":"Återbetalning","cancel":"Avbryt","info-box":{"title":"Observera:","information":"Reservationer med MobilePay avbryts efter 7 dagar. Kom ihåg att skicka och fullfölja dina beställningar."}}}')},s15O:function(e,t,n){},ttHz:function(e,t,n){var r=n("wneZ");r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);(0,n("P8hj").default)("314a1fb6",r,!0,{})},wneZ:function(e,t,n){}});
//# sourceMappingURL=vipps-mobilepay-epayment.js.map
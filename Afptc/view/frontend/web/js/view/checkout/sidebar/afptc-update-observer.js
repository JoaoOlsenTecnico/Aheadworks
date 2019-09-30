/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'uiComponent',
    'Aheadworks_Afptc/js/action/checkout/update'
], function (Component, updateCheckout) {
    "use strict";

    return Component.extend({
        defaults: {
            imports: {
                responseStatus: 'awAfptcPromoProducts:responseStatus'
            },
            listens: {
                responseStatus: 'responseStatusCheck'
            }
        },

        /**
         * Check response status and update checkout
         *
         * @param {Boolean} status
         */
        responseStatusCheck: function (status) {
            if (status) {
                updateCheckout();
            }
        }
    });
});

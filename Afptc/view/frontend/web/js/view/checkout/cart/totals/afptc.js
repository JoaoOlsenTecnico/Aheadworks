/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Aheadworks_Afptc/js/view/checkout/summary/afptc'
], function (Component) {
    "use strict";

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/view/checkout/cart/totals/afptc'
        },

        /**
         * {@inheritdoc}
         */
        isDisplayed: function () {
            return this.getPureValue() != 0;
        }
    });
});

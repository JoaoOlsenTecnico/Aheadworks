/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals'
], function (Component, totals) {
    "use strict";

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/view/checkout/summary/afptc'
        },

        /**
         * Order totals
         *
         * @return {Object}
         */
        totals: totals.totals(),

        /**
         * Is display afptc totals
         *
         * @return {boolean}
         */
        isDisplayed: function() {
            return this.isFullMode() && this.getPureValue() != 0;
        },

        /**
         * Get total value
         *
         * @return {number}
         */
        getPureValue: function() {
            var price = 0;

            if (this.totals) {
                var afptc = totals.getSegment('aw_afptc');

                if (afptc) {
                    price = afptc.value;
                }
            }
            return price;
        },

        /**
         * Get total value
         *
         * @return {string}
         */
        getValue: function() {
            return this.getFormattedPrice(this.getPureValue());
        }
    });
});

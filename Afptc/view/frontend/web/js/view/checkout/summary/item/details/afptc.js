/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'uiElement'
], function (Component) {
    "use strict";

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/view/checkout/summary/item/details/afptc'
        },

        /**
         * Retrieve qty label value
         * @param {Object} item
         * @returns {String}
         */
        getQtyLabelValue: function (item) {
            if (item.extension_attributes && item.extension_attributes.aw_afptc_qty_label) {
                return item.extension_attributes.aw_afptc_qty_label;
            }

            return '';
        }
    });
});

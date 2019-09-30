/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Aheadworks_Afptc/js/ui/form/element/rule/field-with-preview'
], function (PreviewField) {
    'use strict';

    return PreviewField.extend({
        defaults: {
            imports: {
                productData: '${ $.provider }:data.product_selected'
            },
            listens: {
                productData: 'updateField'
            },
            modules: {
                productListing: '${ $.ns }.${ $.ns }.aw_afptc_rule_product_listing_modal.aw_afptc_rule_product_listing'
            }
        },

        /**
         * Update input value with new coming sku
         */
        updateField: function () {
            var productDataLength = this.productData.length - 1,
                val = '';

            this.productData.forEach(function(selectedValue, index) {
                val = val + selectedValue.sku;
                if (index < productDataLength) {
                    val = val + ', ';
                }
            });
            this.value(val);
        },

        /**
         *  Callback when value is changed by user
         */
        userChanges: function () {
            var skuArray = this._prepareProductSkuArray();

            this._super();
            this.productListing().setExternalValue(skuArray);
        },

        /**
         * Prepare array with selected product sku
         *
         * @returns {Array}
         * @private
         */
        _prepareProductSkuArray: function () {
            if (this.value()) {
                return this.value().split(',').map(function (sku) {
                    return {
                        sku: sku.trim()
                    };
                });
            }
            return [];
        },

        /**
         * Enable preview mode
         */
        enablePreview: function () {
            this.previewMode(true);
        }
    });
});

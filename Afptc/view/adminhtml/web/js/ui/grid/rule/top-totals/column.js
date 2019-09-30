/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'underscore',
    'uiElement',
    'Magento_Catalog/js/price-utils'
], function (_, Element, priceUtils) {
    'use strict';

    return Element.extend({
        defaults: {
            imports: {
                priceFormat: '${ $.provider }:data.priceFormat'
            }
        },

        /**
         * Initializes observable properties
         *
         * @returns {Widget} Chainable
         */
        initObservable: function () {
            this._super()
                .track([
                    'priceFormat'
                ]);

            return this;
        },

        /**
         * Ment to preprocess data associated with a current columns' field
         *
         * @param {Object} record - Data to be preprocessed
         * @returns {String}
         */
        getValue: function (record) {
            return this.formatted(record[this.index], this.dataType);
        },

        /**
         * Ment to preprocess data associated with a current columns' field
         *
         * @param {Object} record - Data to be preprocessed
         * @returns {String}
         */
        getCompareValue: function (record) {
            var formattedValue = this.formatted(record[this.compare.index], this.compare.dataType),
                label = this.compare.label;

            return label ? label.replace('{n}', formattedValue) : formattedValue;
        },

        /**
         * Check if compare enabled
         *
         * @returns {Boolean}
         */
        isEnabledCompare: function () {
            return _.isObject(this.compare) && this.compare.enabled;
        },

        /**
         * Formatted value by type
         * @param {String} value
         * @param {String} type
         * @returns {String}
         */
        formatted: function (value, type) {
            var formattedValue;

            switch (type) {
                case 'percent':
                    value = !_.isUndefined(value) ? value : 0;
                    formattedValue = String(Math.abs(Number(value * 1)).toFixed(2)) + '%';
                    break;
                case 'price':
                    formattedValue = priceUtils.formatPrice(value, this.priceFormat);
                    break;
                case 'decimal':
                    formattedValue = String(Number(value * 1).toFixed(2));
                    break;
                default:
                    formattedValue = String(Number(value * 1).toFixed(0));
            }

            return formattedValue;
        }
    });
});

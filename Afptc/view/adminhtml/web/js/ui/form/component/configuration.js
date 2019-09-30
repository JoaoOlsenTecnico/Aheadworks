/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'uiCollection',
    'uiLayout',
    'mageUtils',
    'underscore'
], function ($, Collection, layout, utils, _) {
    'use strict';

    var optionDataScope = 'option';

    return Collection.extend({
        defaults: {
            modules: {
                popup: '${ $.parentName }',
                externalSource: '${ $.externalSource }'
            },
            externalValue: '${ $.externalDataScope }',
            exports: {
                params:'${ $.provider }:productParams'
            },
            listens: {
                '${ $.provider }:loaded': 'onDataLoaded'
            },
            product: [],
            rowIndex: '',
            links: {
                product: '${ $.provider }:data.product'
            }
        },

        /**
         * Configure product
         *
         * @param rowIndex
         * @param productId
         */
        configure: function (rowIndex, productId) {
            var params = {
                'id': productId
            };

            this.popup().openModal();
            if (this.rowIndex !== rowIndex) {
                this.destroyChildren();
            }
            this.rowIndex = rowIndex;
            this.set('params', params);
        },

        /**
         * Listener of the items provider children array changes
         */
        onDataLoaded: function () {
            this.destroyChildren();
            if (this.product) {
                this.addProduct();
            }
        },

        /**
         * Create product instance
         *
         * @param {Object} productConfig
         * @returns {Configuration} Chainable
         */
        addProduct: function () {
            var product = {
                name: 'product',
                dataScope: 'product'
            };

            var optionData = this._getDataFromExternalSource(optionDataScope);
            if (optionData) {
                this.set('product.' + optionDataScope, _.extend(optionData, this.product.option));
            }

            product = utils.extend({}, this.productConfig, product);
            layout([product]);

            return this;
        },

        /**
         * Update external source
         */
        updateExternalSource: function() {
            var data = this._prepareConfigurableItemOptions();
            this._setDataToExternalSource(optionDataScope + '.configurable_item_options', data);
            this._setDataToExternalSource(optionDataScope + '.default_values', this.product.super_attribute);
        },

        /**
         * Set data to external source
         *
         * @param {String} dataScope
         * @param {Object} value
         * @private
         */
        _setDataToExternalSource: function(dataScope, value) {
            this.externalSource().set(
                this.externalValue + '.' + this.rowIndex + '.' + dataScope, value
            );
        },

        /**
         * Get data from external source
         *
         * @param {String} dataScope
         * @private
         */
        _getDataFromExternalSource: function(dataScope) {
            return this.externalSource().get(
                this.externalValue + '.' + this.rowIndex + '.' + dataScope
            );
        },

        /**
         * Prepare Configuration item options
         *
         * @returns {Array}
         * @private
         */
        _prepareConfigurableItemOptions: function() {
            var result = [];
            var selectedAttributes = this.product.super_attribute;
            var allAttributes = this.product.option.spConfig.attributes;

            _.each(selectedAttributes, function (value, attribute) {
                if (value) {
                    result.push({
                        'option_id': attribute,
                        'option_id_label': allAttributes[attribute].label,
                        'option_value': value,
                        'option_value_label': _.findWhere(allAttributes[attribute].options, {
                            id: value
                        }).label
                    });
                }
            });

            return result;
        }
    });
});
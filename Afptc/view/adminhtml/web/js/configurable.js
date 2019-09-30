/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'Magento_ConfigurableProduct/js/configurable',
    'prototype'
], function (jQuery) {
    'use strict';

    if (typeof AwAfptcProductConfig === 'undefined') {
        window.AwAfptcProductConfig = {};
    }

    /**
     * AwAfptcProductConfig class
     *
     * @type {Product.Config}
     */
    AwAfptcProductConfig = Class.create(Product.Config, {

        /**
         * Configure for values.
         */
        configureForValues: function () {
            if (this.values) {
                this.settings.each(function (element) {
                    var attributeId = element.attributeId;

                    element.value = typeof this.values[attributeId] === 'undefined' ? '' : this.values[attributeId];
                    if (element.value) {
                        jQuery(element).trigger('change');
                    }
                    this.configureElement(element);
                }.bind(this));
            }
        },

        /**
         * Reset children elements
         *
         * @param {Object} element
         */
        resetChildren: function (element) {
            var i;

            if (element.childSettings) {
                for (i = 0; i < element.childSettings.length; i++) {
                    element.childSettings[i].selectedIndex = 0;
                    element.childSettings[i].disabled = true;
                    jQuery(element.childSettings[i]).trigger('change');

                    if (element.config) {
                        this.state[element.config.id] = false;
                    }
                }
            }
        }
    });
});

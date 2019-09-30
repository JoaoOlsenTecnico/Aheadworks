/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'Aheadworks_Afptc/js/components/promo-products/product/options-renderer/renderer-abstract',
    'uiLayout',
    'mageUtils',
    'rjsResolver',
    'Aheadworks_Afptc/js/configurable',
    'prototype'
], function (jQuery, _, Component, layout, utils, resolver) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/components/promo-products/product/options/configurable'
        },

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            this._super()
                .initChildren();

            resolver(this.initConfigurableWidgets, this);

            return this;
        },

        /**
         * Init child components
         *
         * @returns {Component}
         */
        initChildren: function () {
            _.each(this.options.attributes, function (attributeData) {
                this._createFieldComponent(attributeData);
            }, this);

            return this;
        },

        /**
         * Initialize configurable widgets
         */
        initConfigurableWidgets: function () {
            var config = this.options.spConfig;

            config.disablePriceReload = true;
            if (this.options.default_values) {
                config.defaultValues = this.options.default_values;
            }

            new AwAfptcProductConfig(config);
        },

        /**
         * Create field component
         *
         * @param {Object} attributeData
         */
        _createFieldComponent: function (attributeData) {
            var attributeFieldConfig = {
                parent: this.name,
                name: attributeData.code,
                attributeId: attributeData.id,
                dataScope: attributeData.id,
                label: attributeData.label,
                sortOrder: 0,
                validation: {'required-entry': false},
                caption: 'Choose an Option...',
                options: attributeData.options,
                customScope: this.customScope
            };

            attributeFieldConfig = utils.extend({}, this.attributeFieldConfig, attributeFieldConfig);

            layout([attributeFieldConfig]);
        }
    });
});

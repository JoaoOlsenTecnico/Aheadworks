/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'Aheadworks_Afptc/js/components/promo-products/product/options-renderer/renderer-abstract',
    'uiLayout',
    'mageUtils',
    'awAfptcConfigurable',
    'priceBox'
], function ($, _, Component, layout, utils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/components/promo-products/product/options/configurable'
        },

        _loadedOptionCounter: 0,
        _optionDeps: [],

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            this._super()
                .initChildren();

            _.bindAll(
                this,
                'initConfigurableWidgets'
            );

            $.async('.item-options .configurable-option', this.name, this.afterRender.bind(this));
            return this;
        },

        /**
         * Init child components
         *
         * @returns {Component}
         */
        initChildren: function () {
            this._optionDeps = [];
            this._loadedOptionCounter = 0;

            _.each(this.options.attributes, function (attributeData) {
                this._createFieldComponent(attributeData);
                this._optionDeps.push(attributeData.code);
            }, this);

            return this;
        },

        /**
         * After render.
         * It is used to check if all options are rendered and initialize widget after.
         */
        afterRender: function () {
            this._loadedOptionCounter++;

            if (this._loadedOptionCounter === this._optionDeps.length) {
                _.delay(this.initConfigurableWidgets, 400);
            }
        },

        /**
         * Initialize configurable widgets
         */
        initConfigurableWidgets: function () {
            var spConfig = this.options.spConfig,
                uidSelector = '#' + this.customScope,
                priceBoxSelector = uidSelector + ' .price-box';

            spConfig['containerId'] = uidSelector;

            $(priceBoxSelector).priceBox({
                'priceConfig': {
                    priceFormat: this.options.spConfig.priceFormat,
                    prices: this.options.spConfig.prices
                }
            });

            $(priceBoxSelector).configurable({
                priceHolderSelector: priceBoxSelector,
                mediaGallerySelector: uidSelector + ' [data-gallery-role=gallery-placeholder]',
                slyOldPriceSelector: uidSelector + ' .sly-old-price',
                normalPriceLabelSelector: uidSelector + ' .normal-price .price-label',
                tierPriceTemplateSelector: uidSelector + ' #tier-prices-template',
                tierPriceBlockSelector: uidSelector + ' [data-role="tier-price-block"]',
                spConfig: spConfig,
                gallerySwitchStrategy: this.options.gallerySwitchStrategy
            });

            this.set('loaderStatusData', { 'optionDeps': this._optionDeps });
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
                validation: {'required-entry': true},
                caption: 'Choose an Option...',
                options: attributeData.options,
                customScope: this.customScope
            };

            attributeFieldConfig = utils.extend({}, this.attributeFieldConfig, attributeFieldConfig);

            layout([attributeFieldConfig]);
        }
    });
});

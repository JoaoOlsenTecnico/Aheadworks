/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'underscore',
    'uiComponent',
    'uiLayout',
    'mageUtils'
], function (_, Component, layout, utils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/ui/form/components/product',
            rendererList: [],
            links: {
                product: '${ $.provider }:${ $.dataScope }'
            },
            listens: {
                '${ $.provider }:data.validate': 'validate'
            }
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super()
                .createOptions();

            return this;
        },

        /**
         * Initializes observable properties
         *
         * @returns {Product} Chainable
         */
        initObservable: function () {
            this._super()
                .observe({
                    product: {}
                });

            return this;
        },

        /**
         * Create options for product
         */
        createOptions: function () {
            var optionsData = this.product().option;

            if (_.size(optionsData) > 0) {
                this._createOptionsRendererComponent(this.product().type, optionsData);
            }
        },

        /**
         * Create options renderer component
         *
         * @param {String} type
         * @param {Object} options
         */
        _createOptionsRendererComponent: function (type, options) {
            var optionsConfig = {
                parent: this.name,
                name: 'options',
                dataScope: 'super_attribute',
                displayArea: 'options',
                options: options
            };

            optionsConfig = utils.extend({}, this.optionsConfig, optionsConfig);
            optionsConfig = type !== 'default' && !_.isUndefined(this.rendererList[type])
                ? utils.extend({}, optionsConfig, this.rendererList[type])
                : optionsConfig;

            layout([optionsConfig]);
        }
    });
});

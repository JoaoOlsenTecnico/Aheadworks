/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiComponent',
    'uiLayout',
    'mageUtils'
], function (ko, _, Component, layout, utils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/components/promo-products/product',
            rendererList: [],
            modules: {
                promoProducts: '${ $.parentName }'
            },
            links: {
                product: '${ $.provider }:${ $.dataScope }'
            },
            exports: {
                qty: '${ $.provider }:${ $.dataScope }.qty',
                checked: '${ $.provider }:${ $.dataScope }.checked'
            },
            listens: {
                '${ $.provider }:data.validate': 'validate',
                loaderStatusData: 'updateLoaderStatus',
                isLoading: 'onProductRender',
                qty: 'qtyUpdated',
                checked: 'checkboxChanged'
            }
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super()
                .disableIfNeeded()
                .createOptions()
                .initOptionVisibility();

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
                    checked: false,
                    visible: true,
                    disabled: false,
                    product: {},
                    isLoading: true,
                    isOptionVisible: false,
                    loaderStatusData: {},
                    qty: 0
                });

            this._qty = ko.pureComputed({
                read: function () {
                    return this.qty();
                },

                /**
                 * Validates input field prior to updating 'qty' property
                 */
                write: function (value) {
                    this.qty(this._modifyQtyValue(value));
                    this._qty.notifySubscribers(this.qty());
                },

                owner: this
            });

            return this;
        },

        isQtyVisible: function () {
            var ruleId = this.product().rule_id;

            return this.promoProducts().getQtyToGiveByRule(ruleId) > 1;
        },

        /**
         * Event on qty updated
         */
        qtyUpdated: function () {
            if (this.qty() > 0) {
                this.checked(true);
            } else {
                this.checked(false);
            }
            this.promoProducts().productChecked(this);
        },

        /**
         * Event on checkbox changed
         */
        checkboxChanged: function () {
            if (this.checked() && this.qty() === 0) {
                this.qty(1);
            } else if (!this.checked() && this.qty() > 0) {
                this.qty(0);
            }
        },

        /**
         * Decrease Qty value
         */
        decreaseQty: function () {
            var qty = this.qty();

            qty--;
            this.qty(this._modifyQtyValue(qty));
        },

        /**
         * Increase Qty value
         */
        increaseQty: function () {
            var qty = this.qty();

            qty++;
            this.qty(this._modifyQtyValue(qty));
        },

        /**
         * Toggle option visibility
         */
        toggleOptionVisibility: function () {
            this.isOptionVisible(!this.isOptionVisible());
        },

        /**
         * Update loader status
         */
        updateLoaderStatus: function() {
            var loaderData = this.loaderStatusData(), loaderStatus;

            if (loaderData.optionDeps) {
                loaderStatus = {
                    parent: this.name,
                    name: 'loaderStatus',
                    deps: []
                };

                _.each(this.loaderStatusConfig.defaultDeps, function (defaultDep) {
                    loaderStatus.deps.push(this.name + '.' + defaultDep);
                }.bind(this));

                if (loaderData.optionDeps.length) {
                    loaderStatus.deps.push(this.name + '.options');
                    _.each(loaderData.optionDeps, function (optionDep) {
                        loaderStatus.deps.push(this.name + '.options.' + optionDep);
                    }.bind(this));
                }

                loaderStatus = utils.extend({}, this.loaderStatusConfig, loaderStatus);
                layout([loaderStatus]);
            }
        },

        /**
         * Retrieve component unique id
         *
         * @returns {String}
         */
        getUid: function () {
            return this.product().key;
        },

        /**
         * Disable element
         *
         * @returns {Product} Chainable
         */
        disable: function () {
            this.disabled(true);
            this.checked(false);
            this.qty(0);

            return this;
        },

        /**
         * Enable element
         *
         * @returns {Product} Chainable
         */
        enable: function () {
            this.disabled(false);

            return this;
        },

        /**
         * On product render handler
         */
        onProductRender: function() {
            if (!this.isLoading()) {
                this.disableIfNeeded()
                    .promoProducts('addNextProduct');
            }
        },

        /**
         * Initialize option visibility
         */
        initOptionVisibility: function() {
            this.isOptionVisible(!this.product().is_option_block_hidden);
        },

        /**
         * Disable product if needed
         *
         * @returns {Product}
         */
        disableIfNeeded: function() {
            var ruleId = this.product().rule_id;

            if (this.promoProducts().isNeedToHideProducts(ruleId) && !this.isSelected()) {
                this.disable();
            }

            return this;
        },

        /**
         * Check product disabled or not
         *
         * @returns {Boolean}
         */
        isDisabled: function () {
            return this.disabled();
        },

        /**
         * Check if product selected
         *
         * @returns {Boolean}
         */
        isSelected: function () {
            return this.checked() && this.qty() > 0;
        },

        /**
         * Validate
         */
        validate: function () {
            if (this.checked()) {
                this.source.trigger(this.getUid() + '.data.validate');
                this._checkOptionValidation();
            }
        },

        /**
         * Create options for product
         *
         * @returns {Product}
         */
        createOptions: function () {
            var optionsData = this.product().option,
                optionDeps = [];

            if (_.size(optionsData) > 0) {
                this._createOptionsRendererComponent(this.product().type, optionsData);
            } else {
                this.loaderStatusData({
                    'optionDeps': optionDeps
                });
            }
            return this;
        },

        /**
         * Retrieve product data as array for rendering price
         *
         * @returns {Object}
         */
        getDataForRenderingPrice: function () {
            return [this.product()];
        },

        /**
         * Modify qty value
         *
         * @param {String|Number} value
         * @return {Number}
         * @private
         */
        _modifyQtyValue: function (value) {
            var ruleId = this.product().rule_id, availableQty;

            availableQty = this.promoProducts().getLeftProductsQtyByRule(ruleId, this.product().key);

            value = Number(value);
            value = value < 0 ? 0 : (value > availableQty ? availableQty : value);

            return value;
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
                options: options,
                customScope: this.getUid(),
                exports: {
                    'loaderStatusData': this.name + ':loaderStatusData'
                }
            };

            optionsConfig = utils.extend({}, this.optionsConfig, optionsConfig);
            optionsConfig = type !== 'default' && !_.isUndefined(this.rendererList[type])
                ? utils.extend({}, optionsConfig, this.rendererList[type])
                : optionsConfig;

            layout([optionsConfig]);
        },

        /**
         * Check option validation and expand section if something is not correct
         */
        _checkOptionValidation: function() {
            var optionSection = this.getRegion('options')();

            _.each(optionSection, function (option) {
                _.each(option.elems(), function (elem) {
                    if (elem.error()) {
                        this.isOptionVisible(true);
                    }
                }.bind(this));
            }.bind(this));
        }
    });
});

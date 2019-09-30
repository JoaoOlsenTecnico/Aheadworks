/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'mageUtils',
    'uiLayout',
    'Magento_Ui/js/modal/modal-component',
    'mage/translate',
    'uikit',
    'Aheadworks_Afptc/js/model/promo/popup/page-route-checker',
    'mage/cookies',
    'jquery/file-uploader'
], function ($, _, utils, layout, Modal, $t, UIkit, pageRouteChecker) {
    'use strict';

    var DO_NOT_SHOW_COOKIE_NAME = 'aw_afptc_popup_do_not_show';

    return Modal.extend({
        defaults: {
            cookieLifetime: 31536000,
            selectedTextDefault: $t('Selected {param1} of {param2}'),
            options: {
                type: 'popup',
                modalClass: 'aw-afptc__main-popup',
                responsive: false,
                innerScroll: true
            },
            items: [],
            template: 'Aheadworks_Afptc/components/promo-products',
            imports: {
                generalQtyToGive: '${ $.provider }:data.count',
                items: '${ $.provider }:data.items',
                rulesConfig: '${ $.provider }:data.rules_config',
                wasShown: '${ $.provider }:data.modified',
                headerText: '${ $.provider }:data.header_text',
                quantityNoticeActive: '${ $.provider }:data.quantity_notice_active'
            },
            listens: {
                '${ $.provider }:reloaded': 'onDataReloaded'
            },
            uikitSliderConfig: {
                sets: true
            },
            mainModalSelector: '.modal-inner-wrap',
            forcePageReloadingRouteList: ['checkout/cart']
        },
        _productIndex: 0,

        /**
         * Initializes observable properties
         *
         * @returns {PromoProducts} Chainable
         */
        initObservable: function () {
            this._super()
                .observe({
                    doNotShow: $.parseJSON($.mage.cookies.get(DO_NOT_SHOW_COOKIE_NAME)),
                    wasShown: false,
                    errorValidationMessage: '',
                    headerText: '',
                    selectedText: '',
                    quantityNoticeActive: false,
                    disabledAddToCart: true
                })
                .observe([
                    'responseData',
                    'responseStatus'
                ]);

            return this;
        },

        /**
         * Listener of the items provider children array changes
         */
        onDataReloaded: function () {
            this.destroyChildren();
            if (this.items.length) {
                this._productIndex = 0;
                this.addNextProduct();
                this.displayPopup();
                this.customWidthPopup();
            }
        },

        /**
         * Add special class if products less than 2
         */
        customWidthPopup: function() {
            var cls = '',
                $mainModal = $(this.mainModalSelector),
                $productList = $mainModal.find('.product-list');

            $mainModal.removeClass('aw-afptc-col-2 aw-afptc-col-1');

            if (this.items.length === 2) {
                cls = 'aw-afptc-col-2';
                $productList.removeClass('uk-child-width-1-3@m');
            } else if (this.items.length === 1) {
                cls = 'aw-afptc-col-1';
                $productList.removeClass('uk-child-width-1-2@s uk-child-width-1-3@m');
            } else {
                $productList.addClass('uk-child-width-1-2@s uk-child-width-1-3@m');
            }

            $mainModal.addClass(cls);
            return this;
        },

        /**
         * Add next product if possible
         */
        addNextProduct: function() {
            if (this.items[this._productIndex]) {
                this._addProduct(this.items[this._productIndex], this._productIndex);
                this._productIndex++;
            }
            this._updateSelectedText();
        },

        /**
         * Display popup
         */
        displayPopup: function () {
            if (!this.doNotShow() && this.wasShown()) {
                this.openModal();
                this.wasShown(true);
            }
        },

        /**
         * Handler function which is supposed to be invoked when
         * uikit slider element has been rendered
         *
         * @param {HTMLElement} uikitSlider
         */
        onSliderRender: function (uikitSlider) {
            this.initSliderLoader(uikitSlider);
        },

        /**
         * Initializes slider loader
         *
         * @param {HTMLElement} uikitSlider
         * @returns {PromoProducts} Chainable
         */
        initSliderLoader: function (uikitSlider) {
            _.extend(this.uikitSliderConfig, {
                autoplay:   false
            });
            UIkit.slider(uikitSlider, this.uikitSliderConfig);

            return this;
        },

        /**
         * Event for 'Don't show' flag
         */
        toggleDoNotShow: function () {
            $.mage.cookies.set(DO_NOT_SHOW_COOKIE_NAME, this.doNotShow(), {lifetime: this.cookieLifetime});
        },

        /**
         * Add selected products to cart
         */
        addToCart: function () {
            this.validate();

            if (!this.source.get('params.invalid')) {
                this.submit();
                this.closeModal();
            }
        },

        /**
         * Submits form
         */
        submit: function () {
            var source = this.source,
                isAjaxRequest = true;

            if (pageRouteChecker.isAllowedForCurrentPage(this.forcePageReloadingRouteList)) {
                isAjaxRequest = false;
                window.FORM_KEY = $.mage.cookies.get('form_key');
            }

            source.save({
                redirect: true,
                ajaxSave: isAjaxRequest,
                ajaxSaveType: 'default',
                response: {
                    data: this.responseData,
                    status: this.responseStatus
                }
            });
        },

        /**
         * Validates each element and returns true, if all elements are valid
         */
        validate: function () {
            this.source.set('params.invalid', false);
            this._validateAtLeastOneSelected();
            this.source.trigger('data.validate');
        },

        /**
         * Product selection event
         *
         * @param {UiClass} component
         */
        productChecked: function (component) {
            this.errorValidationMessage('');
            this._toggleProductVisibility(component);
            this._updateSelectedText();
            this.disabledAddToCart(this._getSelectedProductsQty() === 0);
        },

        /**
         * Check that at least one promo product is selected
         */
        _validateAtLeastOneSelected: function () {
            var atLeastOneSelected = false;

            _.each(this.elems(), function (elem) {
                if (elem.isSelected()) {
                    atLeastOneSelected = true;
                }
            });

            if (!atLeastOneSelected) {
                this.errorValidationMessage('Please specify promo product(s).');
                this.source.set('params.invalid', true);
            }
        },

        /**
         * Check if need to hide products by rule
         *
         * @param {int} ruleId
         * @returns {boolean}
         */
        isNeedToHideProducts: function(ruleId) {
            var ruleConfig = this._getRuleConfigByRule(ruleId);

            return this._getSelectedProductsQtyByRule(ruleId) === ruleConfig.qty_to_give;
        },

        /**
         * Retrieve left products qty by rule
         *
         * @param {Number} ruleId
         * @param {String} productKey
         * @returns {Number}
         */
        getLeftProductsQtyByRule: function (ruleId, productKey) {
            var ruleConfig = this._getRuleConfigByRule(ruleId);

            return Math.max(0, ruleConfig.qty_to_give - this._getSelectedProductsQtyByRule(ruleId, productKey));
        },

        /**
         * Retrieve qty to give by rule
         *
         * @param {Number} ruleId
         * @returns {Number}
         */
        getQtyToGiveByRule: function (ruleId) {
            var ruleConfig = this._getRuleConfigByRule(ruleId);

            return Math.max(0, ruleConfig.qty_to_give);
        },

        /**
         * Create product instance
         *
         * @param {Object} productConfig
         * @param {Number} index
         * @returns {PromoProducts} Chainable
         */
        _addProduct: function (productConfig, index) {
            var product = {
                name: 'product_' + productConfig.key,
                dataScope: 'data.items.' + index
            };

            product = utils.extend({}, this.productConfig, product);
            layout([product]);

            return this;
        },

        /**
         * Toggle product visibility
         *
         * @param {UiClass} component
         * @returns {PromoProducts} Chainable
         */
        _toggleProductVisibility: function (component) {
            var ruleId = component.product().rule_id;

            _.each(this.elems(), function (elem) {
                if (elem !== component && elem.product().rule_id === ruleId) {
                    if (this.isNeedToHideProducts(ruleId) && !elem.isSelected()) {
                        elem.disable();
                    } else {
                        elem.enable();
                    }
                }
            }.bind(this));

            return this;
        },

        /**
         * Retrieve rule config by rule id
         *
         * @param {Number} ruleId
         * @returns {Object}
         */
        _getRuleConfigByRule: function (ruleId) {
            return _.find(this.rulesConfig, function (ruleConfig) {
                return ruleConfig.rule_id == ruleId;
            });
        },

        /**
         * Retrieve selected product qty by rule
         *
         * @param {Number} ruleId
         * @param {String} productKey
         * @returns {Number}
         */
        _getSelectedProductsQtyByRule: function (ruleId, productKey) {
            var selectedQty = 0,
                productKey = productKey || null;

            _.each(this.elems(), function (elem) {
                if (elem.isSelected() && elem.product().rule_id === ruleId && productKey !== elem.product().key) {
                    selectedQty += elem.qty();
                }
            });
            return selectedQty;
        },

        /**
         * Retrieve selected product qty
         *
         * @returns {Number}
         */
        _getSelectedProductsQty: function () {
            var qtySelected = 0;

            _.each(this.elems(), function (elem) {
                if (elem.isSelected()) {
                    qtySelected += elem.qty();
                }
            });
            return qtySelected;
        },

        /**
         * Update selected text after render product or product selected
         */
        _updateSelectedText: function () {
            var text;

            text = this.selectedTextDefault.replace('{param1}', this._getSelectedProductsQty());
            text = text.replace('{param2}', this.generalQtyToGive);

            this.selectedText(text);
        }
    })
});

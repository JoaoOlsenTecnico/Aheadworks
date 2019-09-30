/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'uiElement',
    'Magento_Ui/js/modal/alert'
], function ($, _, Element, alert) {
    'use strict';

    return Element.extend({
        defaults: {
            providerConfig: {
                urls: {
                    configure: ''
                }
            },
            links: {
                externalValue: '${ $.externalProvider }:${ $.dataScope }'
            }
        },

        /**
         * Initializes observable properties
         *
         * @returns {Provider} Chainable
         */
        initObservable: function () {
            this._super();
            this.observe({
                'productParams' : {}
            });
            this.productParams.subscribe(function (params) {
                this.loadData(params);
            }, this);

            return this;
        },

        /**
         * Load data for provider
         *
         * @param {Object} params
         */
        loadData: function (params) {
            $.ajax({
                url: this.providerConfig.urls.configure,
                data: params,
                showLoader: true,
                dataType: 'json',
                success: function (response) {
                    if (response.ajaxExpired) {
                        window.location.href = response.ajaxRedirect;
                    }
                    if (!response.error) {
                        this.setData(response.product);
                    } else {
                        this.showError(response.message);
                    }
                }.bind(this)
            });
        },

        /**
         * Overrides current data with a provided one.
         *
         * @param {Object} data - New data object
         * @returns {Provider} Chainable
         */
        setData: function (data) {
            this.set('data.product', data)
                .trigger('loaded');

            return this;
        },

        /**
         * Show error popup
         *
         * @param {string} errorMessage
         */
        showError: function (errorMessage) {
            alert({
                content: errorMessage
            });
        }
    });
});

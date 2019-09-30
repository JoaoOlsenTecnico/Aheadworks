/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'uiElement',
    'rjsResolver',
    'Magento_Ui/js/form/client',
    'uiLayout',
    'uiRegistry'
], function ($, _, Element, resolver, Client, layout, registry) {
    'use strict';

    /**
     * Compares items
     *
     * @param {Array} base
     * @param {Array} current
     * @returns {Boolean} result
     */
    function compareItems(base, current) {
        var index = 0,
            length = base.length;

        if (base.length !== current.length) {
            return false;
        }

        for (index; index < length; index++) {
            if (!compareItem(base[index], current[index])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Compare item
     *
     * @param {Object} baseItem
     * @param {Object} currentItem
     * @returns {Boolean} result
     */
    function compareItem(baseItem, currentItem) {
        var fieldsToCompare = ['sku', 'ruleId'],
            result = true;

        _.each(fieldsToCompare, function (field) {
            if (baseItem[field] !== currentItem[field]) {
                result = false;
            }
        });
        return result;
    }

    return Element.extend({
        defaults: {
            clientConfig: {
                urls: {
                    save: ''
                }
            },
            providerConfig: {
                urls: {
                    get: ''
                }
            },
            itemFieldsToSave: ['checked', 'rule_id', 'qty', 'sku', 'super_attribute'],
            storageConfig: {
                component: 'Aheadworks_Afptc/js/components/promo-products/storage',
                provider: '${ $.storageConfig.name }',
                name: '${ $.name }_storage'
            },
            params: {}
        },

        /**
         * Initializes component
         *
         * @returns {Provider} Chainable
         */
        initialize: function () {
            this._super()
                .initClient()
                .initStorage()
                .initEventListening();
            this.loadData();
            return this;
        },

        /**
         * Initialize event listeners
         *
         * @returns {Provider} Chainable
         */
        initEventListening: function () {
            order.itemsLoaded = order.itemsLoaded.wrap(function(proceed) {
                proceed();
                this.loadData();
            }.bind(this));
        },

        /**
         * Initializes observable properties
         *
         * @returns {Provider} Chainable
         */
        initObservable: function () {
            this._super();

            this.observe('promoOffer');
            this.promoOffer.subscribe(function (updatedPromoOffer) {
                this.onReload(updatedPromoOffer);
            }, this);

            return this;
        },

        /**
         * Load data for provider
         */
        loadData: function () {
            $.ajax({
                url: this.providerConfig.urls.get,
                data: {ajax: true},
                showLoader: true,
                dataType: 'json',
                success: function (response) {
                    if (response.ajaxExpired) {
                        window.location.href = response.ajaxRedirect;
                    }
                    if (!response.error) {
                        this.promoOffer(response.promoOffer);
                    } else {
                        alert(response.message);
                    }
                }.bind(this)
            });
        },

        /**
         * Initializes client component
         *
         * @returns {Provider} Chainable
         */
        initClient: function () {
            this.client = new Client(this.clientConfig);

            return this;
        },

        /**
         * Initializes storage component
         *
         * @returns {Provider} Chainable
         */
        initStorage: function () {
            layout([this.storageConfig]);

            return this;
        },

        /**
         * Saves currently available data
         *
         * @param {Object} options
         * @returns {Provider} Chainable
         */
        save: function (options) {
            var data = this.get('data');
            data = this.processDataBeforeSave(data);

            this.client.save(data, options);

            return this;
        },

        /**
         * Processes data before applying it
         *
         * @param {Object} data
         * @returns {Object}
         */
        processDataBeforeSave: function (data) {
            var items = data.items || [],
                self = this,
                processedData = {},
                processedItems = [],
                processedItem;

            _.each(items, function (item) {
                if (item.checked) {
                    processedItem = _.pick(item, function(value, key) {
                        return _.indexOf(self.itemFieldsToSave, key) !== -1;
                    });
                    processedItems.push(processedItem);
                }
            });
            processedData['items'] = processedItems;

            return processedData;
        },

        /**
         * Overrides current data with a provided one.
         *
         * @param {Object} data - New data object
         * @returns {Provider} Chainable
         */
        setData: function (data) {
            registry.async(this.storageConfig.name)(
                function () {
                    data = this.processDataBeforeSet(data);
                    this.set('data', data);
                }.bind(this)
            );

            return this;
        },

        /**
         * Processes data before applying it
         *
         * @param {Object} data
         * @returns {Object}
         */
        processDataBeforeSet: function (data) {
            var items = data['items'] || [],
                qtyItems = data['count'] || 0,
                modified = false,
                storageItems,
                storageQtyItems;

            storageItems = _.isArray(this.storage().get('items'))
                ? this.storage().get('items')
                : [];
            storageQtyItems = this.storage().get('qtyItems')
                ? this.storage().get('qtyItems')
                : 0;
            if (items.length > 0
                && (qtyItems !== storageQtyItems || !compareItems(items, storageItems))
            ) {
                this.storage().set('items', items);
                this.storage().set('qtyItems', qtyItems);
                modified = true;
            }
            data['modified'] = modified;

            return data;
        },

        /**
         * Load current data
         */
        loadCurrentData: function () {
            this.onReload(this.promoOffer());
        },

        /**
         * Reloads data
         */
        reload: function() {
            this.trigger('reload');
            customerData.reload(['aw-afptc-promo'], false);
        },

        /**
         * Handles successful data reload
         *
         * @param {Object} data
         */
        onReload: function (data) {
            this.setData(data)
                .trigger('reloaded');
        }
    });
});

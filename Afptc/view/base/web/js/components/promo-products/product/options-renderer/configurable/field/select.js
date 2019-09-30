/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'underscore',
    'Magento_Ui/js/form/element/select'
], function (_, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            elementTmpl: 'Aheadworks_Afptc/components/promo-products/product/options/configurable/field/select'
        },

        /**
         * Initializes regular properties of instance
         *
         * @returns {Select} Chainable
         */
        initConfig: function () {
            this._super();

            _.extend(this, {
                uid: this.getId()
            });

            return this;
        },

        /**
         * Retrieve attribute id
         *
         * @returns {String}
         */
        getId: function () {
            return 'attribute' + this.attributeId;
        },

        /**
         * Retrieve data selector
         *
         * @returns {String}
         */
        getDataSelector: function () {
            return 'super_attribute[' + this.attributeId + ']';
        }
    });
});

/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/abstract'
], function ($, Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            links: {
                value: false
            },
            modules: {
                productConfiguration: '${ $.ns }.${ $.ns }.product_configuration_modal.product-configuration'
            }
        },

        /**
         * Configure record handler.
         *
         * @param {Number} index
         * @param {Number} id
         */
        configureRecord: function (index, id) {
            this.productConfiguration().configure(index, id);
        }
    });
});

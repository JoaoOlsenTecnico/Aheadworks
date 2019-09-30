/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/abstract',
    'underscore'
], function (Abstract, _) {
    'use strict';

    return Abstract.extend({
        defaults: {
            imports: {
                imageValue: '${ $.parentName }.promo_image:value'
            },
            listens: {
                imageValue: 'checkImage'
            }
        },

        /**
         * Check if image is loaded
         * @param {Array} value
         */
        checkImage: function (value) {
            if (_.isEmpty(value)) {
                this.visible(false);
            } else {
                this.visible(true);
            }
        }
    });
});

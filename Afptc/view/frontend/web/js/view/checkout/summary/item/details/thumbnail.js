/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define(['Magento_Checkout/js/view/summary/item/details/thumbnail'], function (Component) {
    'use strict';

    return Component.extend({

        /**
         * Get image src
         *
         * @param {Object} item
         * @return {null}
         */
        getSrc: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].src;
            }
            if (item.extension_attributes && item.extension_attributes.aw_afptc_image_data) {
                return item.extension_attributes.aw_afptc_image_data.src
            }

            return null;
        },

        /**
         * Get image width
         *
         * @param {Object} item
         * @return {null}
         */
        getWidth: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].width;
            }
            if (item.extension_attributes && item.extension_attributes.aw_afptc_image_data) {
                return item.extension_attributes.aw_afptc_image_data.width
            }

            return null;
        },

        /**
         * Get image height
         *
         * @param {Object} item
         * @return {null}
         */
        getHeight: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].height;
            }
            if (item.extension_attributes && item.extension_attributes.aw_afptc_image_data) {
                return item.extension_attributes.aw_afptc_image_data.height
            }

            return null;
        },

        /**
         * Get image alt
         *
         * @param {Object} item
         * @return {null}
         */
        getAlt: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].alt;
            }
            if (item.extension_attributes && item.extension_attributes.aw_afptc_image_data) {
                return item.extension_attributes.aw_afptc_image_data.alt
            }

            return null;
        }
    });
});

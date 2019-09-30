/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Aheadworks_OneStepCheckout/js/view/sidebar/item-details/thumbnail',
    'Aheadworks_OneStepCheckout/js/model/image-data'
], function (Component, imageData) {
    "use strict";
        return Component.extend({

            /**
             * Get src attribute
             *
             * @param {Object} item
             * @returns {string|null}
             */
            getSrc: function(item) {
                if (
                    !imageData.getItemImageDataByItemId(item.item_id)
                    && item.extension_attributes
                    && item.extension_attributes.aw_afptc_image_data
                ) {
                    return item.extension_attributes.aw_afptc_image_data.src
                }

                return this._getItemImageDataProperty(
                    item.item_id,
                    'src',
                    imageData.getPlaceHolderUrl()
                );
            },

            /**
             * Get alt attribute
             *
             * @param {Object} item
             * @returns {string|null}
             */
            getAlt: function(item) {
                if (
                    !imageData.getItemImageDataByItemId(item.item_id)
                    && item.extension_attributes
                    && item.extension_attributes.aw_afptc_image_data
                ) {
                    return item.extension_attributes.aw_afptc_image_data.alt
                }

                return this._getItemImageDataProperty(item.item_id, 'alt', null);
            }
        });
    }
);

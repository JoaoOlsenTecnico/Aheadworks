/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/file-uploader',
    'mageUtils'
], function (Component, utils) {
    'use strict';

    return Component.extend({

        /**
         * Checks if image placeholder is visible
         *
         * @returns {Boolean}
         */
        isVisibleImagePlaceholder: function () {
            return !(!utils.isEmpty(this.value()) && this.value().length);
        }
    });
});

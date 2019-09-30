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
            listens: {
                error: 'checkError'
            }
        },

        /**
         * Prepare preview for field
         *
         * @returns {String}
         */
        getPreview: function() {
            if (this._checkIfValueEmpty()) {
                return '...';
            }
            return this._super();
        },

        /**
         * Check if value is empty
         *
         * @returns {boolean}
         * @private
         */
        _checkIfValueEmpty: function() {
            var value = this.value();

            if (_.isObject(value)) {
                if (_.isEmpty(value)) {
                    return true;
                }
            }

            return !value && value !== 0;
        },

        /**
         * Check validation errors
         */
        checkError: function () {
            if (!this.error()) {
                this.previewMode(true);
            } else {
                this.previewMode(false);
            }
        }
    });
});

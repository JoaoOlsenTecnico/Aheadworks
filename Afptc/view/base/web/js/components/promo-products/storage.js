/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/lib/core/storage/local',
    'uiClass'
], function ($, storage, Class) {
    'use strict';

    var STORAGE_NAMESPACE = 'aw_afptc_storage';

    return Class.extend({
        /**
         * Retrieves value of the specified property
         *
         * @param {String} path
         * @returns {*}
         */
        get: function (path) {
            return storage.get(STORAGE_NAMESPACE + '.' + path);
        },

        /**
         * Sets specified data to the localStorage
         *
         * @param {String} path
         * @param {*} value
         */
        set: function (path, value) {
            storage.set(STORAGE_NAMESPACE + '.' + path, value);
        }
    });
});

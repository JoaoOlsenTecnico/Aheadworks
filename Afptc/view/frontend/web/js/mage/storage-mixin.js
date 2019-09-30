/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'mage/utils/wrapper',
    'Magento_Customer/js/section-config'
], function(wrapper, sectionConfig){
    'use strict';

    return function (storage) {

        /** Override default storage put method and change global to true if there are sections */
        storage.put = wrapper.wrap(storage.put, function(original, url, data, global, contentType) {
            if (sectionConfig.getAffectedSections(url)) {
                global = true;
            }
            return original(url, data, global, contentType);
        });

        storage.delete = wrapper.wrap(storage.delete, function(original, url, global, contentType) {
            if (sectionConfig.getAffectedSections(url)) {
                global = true;
            }
            return original(url, global, contentType);
        });

        return storage;
    };
});

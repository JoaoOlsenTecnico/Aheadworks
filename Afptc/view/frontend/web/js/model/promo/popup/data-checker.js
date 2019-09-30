/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Aheadworks_Afptc/js/model/promo/popup/page-route-checker',
], function (pageRouteChecker) {
    'use strict';

    return {

        /**
         * Check if data can be processed
         *
         * @param {Object} data
         * @returns {Boolean}
         */
        isAllowedToProcess: function (data) {
            return pageRouteChecker.isAllowedForCurrentPage(data.page_routes_to_display_popup);
        }
    };
});

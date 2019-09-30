/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'mage/url',
    'underscore'
], function (url, _) {
    'use strict';

    return {

        /**
         * Check if any route matches current page
         *
         ** @param {Array} routeList
         * @returns {Boolean}
         * @private
         */
        isAllowedForCurrentPage: function (routeList) {
            var link,
                result;

            if (!_.isEmpty(routeList)) {
                result = _.some(routeList, function (route) {
                    link = url.build(route);
                    return location.href.indexOf(link) !== -1
                });
            } else {
                result = true;
            }

            return result;
        }
    };
});

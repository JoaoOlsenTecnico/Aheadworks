/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'uiComponent'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            options: {}
        },

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            this._super();

            return this;
        }
    });
});

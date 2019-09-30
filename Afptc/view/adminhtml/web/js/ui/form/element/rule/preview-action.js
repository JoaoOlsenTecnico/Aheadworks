/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'uiCollection',
    'underscore'
], function (Collection, _) {
    'use strict';

    return Collection.extend({
        defaults: {
            listens: {
                elems: 'initPreviewMode'
            }
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    'visible': true
                });

            return this;
        },

        /**
         * Init observable variable for each child of collection
         */
        initPreviewMode: function () {
            _.each(this.elems(), function (elem) {
                elem.observe({
                    'previewMode': true
                });
            });
        },

        /**
         * Toggle preview mode for element
         * @param {Object} elem
         */
        togglePreviewMode: function(elem) {
            elem.previewMode(!elem.previewMode());

            if (!elem.previewMode()) {
               elem.focused(true);
            }
        },

        /**
         * Enable preview mode for element
         * @param {Object} elem
         */
        enablePreviewMode: function (elem) {
            if (!elem.error()) {
                elem.previewMode(true);
            }
        },

        /**
         * Show element.
         *
         * @returns {Object} Chainable.
         */
        show: function () {
            this.visible(true);

            return this;
        },

        /**
         * Hide element.
         *
         * @returns {Object} Chainable.
         */
        hide: function () {
            this.visible(false);

            return this;
        }
    });
});

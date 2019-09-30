/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'uiElement',
    'uiLayout'
], function ($, UiElement, layout) {
    'use strict';

    return UiElement.extend({
        defaults: {
            template: 'Aheadworks_Afptc/components/promo/info-link',
            infoLinkText: '',
            popupConfig: {
                component: 'Aheadworks_Afptc/js/components/promo/popup',
                template: 'Aheadworks_Afptc/components/promo/popup',
                name: '${ $.name }_popup'
            },
            modules: {
                popup: '${ $.popupConfig.name }'
            }
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super()
                .initPopup();
        },

        /**
         * Initializes popup component.
         *
         * @returns {Info-link} Chainable.
         */
        initPopup: function () {
            layout([this.popupConfig]);

            return this;
        },

        /**
         * Open popup
         */
        openPopup: function () {
            this.popup().show();
        }
    });
});

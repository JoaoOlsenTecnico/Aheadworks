/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/view/promo-offer',
            imports: {
                count: '${ $.provider }:data.count'
            },
            modules: {
                awAfptcPromoProducts: 'awAfptcPromoProducts'
            },
            promoOfferContainer: '[data-role=aw-afptc-promo-offer-link]',
            pageHeader: '.page-header'
        },

        /**
         * Initializes observable properties
         *
         * @returns {PromoOffer} Chainable
         */
        initObservable: function () {
            this._super()
                .track(['count']);

            return this;
        },

        /**
         * Check if display promo link
         *
         * @returns {Boolean}
         */
        isDisplay: function () {
            if (!this.count) {
                $(this.pageHeader).removeClass('aw-afptc-open')
                $(this.promoOfferContainer).hide();
            } else {
                $(this.pageHeader).addClass('aw-afptc-open')
                $(this.promoOfferContainer).show();
            }
            return this.count;
        },

        /**
         * Open modal action
         */
        openModal: function () {
            this.awAfptcPromoProducts().openModal();
        }
    });
});

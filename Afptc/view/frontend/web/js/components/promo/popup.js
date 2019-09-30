/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';

    var prevModal = null;

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Afptc/components/promo/popup',
            modalClass: '.aw-afptc__promo-popup',
            promoClass: '.promo-info-image, .promo-info-link'
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super()
                ._bind();

            return this;
        },

        /**
         * Initializes observable properties
         *
         * @returns {Popup} Chainable
         */
        initObservable: function () {
            this._super()
                .observe({
                    visible: false
                });

            return this;
        },

        /**
         * Bind Event
         */
        _bind: function(){
            $(document).on('click', $.proxy(this._onClickDocument,this));

            $(window).bind( 'orientationchange', $.proxy(this._onChangeOrientation,this));
        },

        /**
         * Orientation Change
         */
        _onChangeOrientation: function () {
            this.changeClassElement();
            this.changePositionOriWindow();
        },


        /**
         * Click Document
         */
        _onClickDocument: function (event) {
            if (!$(event.target).closest(this.modalClass + ", " + this.promoClass).length) {
                this.closePopup();
            }
        },

        /**
         * Hide element
         *
         * @returns {Popup} Chainable
         */
        hide: function () {
            this.visible(false);
            return this;
        },

        /**
         * Show element
         *
         * @returns {Popup} Chainable
         */
        show: function () {
            if (prevModal !== null) {
                prevModal.closePopup();
            }

            prevModal = this.visible(true);
            this.changePositionModal();

            return this;
        },

        /**
         * Close Popup
         */
        closePopup: function () {
            this.hide();
            this.changeClassElement();
            prevModal = null;
        },

        /**
         * Change Class Element
         */
        changeClassElement: function(){
            $(this.modalClass).removeClass('left-side right-side');
        },

        /**
         * Change Position Modal
         */
        changePositionModal: function () {

            var $modal = $(this.modalClass+':visible'),
                e = window.event,
                x = 0,
                xRight = 0,
                width = $(window).width();

            if (e.clientX) {
                x = e.clientX;
            }

            xRight = parseInt(width - Math.abs(x));

            if (x >= xRight) {
                $modal.addClass('right-side');
            } else {
                $modal.addClass('left-side');
            }

            if (parseInt(width) < 400) {
                $modal.css('min-width', (width-30));
            } else {
                $modal.css('min-width', '360px');
            }
        },

        /**
         * Change Position Orieintation Window
         */
        changePositionOriWindow: function () {

            setTimeout(function () {
                var $modal = $('.aw-afptc__promo-popup:visible'),
                    x = 0,
                    xRight = 0,
                    width = $(window).width();

                if ($modal.offset().left) {
                    x = $modal.offset().left;
                    xRight = parseInt(width - Math.abs(x));

                    $modal.removeClass('left-side right-side');

                    if (x >= xRight) {
                        $modal.addClass('right-side');
                    } else {
                        $modal.addClass('left-side');
                    }

                    if (parseInt(width) < 400) {
                        $modal.css('min-width', (width-30));
                    } else {
                        $modal.css('min-width', '360px');
                    }
                }

            },200);
        }
    });
});

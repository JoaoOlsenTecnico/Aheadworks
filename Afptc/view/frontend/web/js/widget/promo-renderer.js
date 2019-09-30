/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'mageUtils'
], function($, utils) {
    "use strict";

    $.widget('mage.awAfptcPromoRenderer', {
        options: {
            selectorToMove: '',
            parentSelector: '',
            actionToInsert: 'appendTo',
            additionalClasses: ''
        },

        /**
         * Initialize widget
         */
        _create: function() {
            var parentElement = this._resolveParentElement(),
                callback = $.proxy(this._init, this);

            if (parentElement instanceof jQuery && parentElement.length) {
                $.async(this.options.selectorToMove, parentElement, callback);
            } else {
                $.async(this.options.selectorToMove, callback);
            }
        },

        /**
         * Promo initialization
         */
        _init: function () {
            var moveToElement = this._resolveMoveToElement();

            if (moveToElement instanceof jQuery && moveToElement.length) {
                this
                    ._move(moveToElement)
                    ._updateMoveToElement(moveToElement)
                    ._updateElement()
                    ._applyAdditionalClasses();
            }
        },

        /**
         * Change promo info link position by selector and action type
         *
         * @param {jQuery} moveToElement
         * @returns {mage.awAfptcPromoRenderer}
         * @private
         */
        _move: function(moveToElement) {
            switch (this.options.actionToInsert) {
                case 'appendTo':
                    this.element.appendTo(moveToElement);
                    break;
                case 'after':
                    $(moveToElement).after(this.element);
                    break;
                case 'before':
                    $(moveToElement).before(this.element);
                    break;
            }

            return this;
        },

        /**
         * Update moveToElement
         *
         * @param {jQuery} moveToElement
         * @returns {mage.awAfptcPromoRenderer}
         */
        _updateMoveToElement: function (moveToElement) {
            moveToElement.css('position', 'relative');

            return this;
        },

        /**
         * Apply additional classes
         *
         * @returns {mage.awAfptcPromoRenderer}
         */
        _applyAdditionalClasses: function () {
            $(this.element).addClass(this.options.additionalClasses);

            return this;
        },

        /**
         * Update element
         *
         * @returns {mage.awAfptcPromoRenderer}
         */
        _updateElement: function () {
            this.element.show();

            return this;
        },

        /**
         * Resolve moveToElement
         *
         * @returns {jQuery}
         */
        _resolveMoveToElement: function () {
            var parentElement, moveToElement;

            if (this.options.selectorToMove) {
                if (utils.isEmpty(this.options.parentSelector)) {
                    moveToElement = $(this.options.selectorToMove);
                } else {
                    parentElement = this.element.closest(this.options.parentSelector);
                    moveToElement = parentElement.find(this.options.selectorToMove);
                }
            }

            return moveToElement;
        },

        /**
         * Resolve parent element
         *
         * @return {undefined}|{jQuery}
         * @private
         */
        _resolveParentElement: function () {
            return utils.isEmpty(this.options.parentSelector)
                ? undefined
                : this.element.closest(this.options.parentSelector);
        }
    });

    return $.mage.awAfptcPromoRenderer;
});

/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'configurable'
], function ($) {
    'use strict';

    $.widget('awafptc.configurable', $.mage.configurable, {

        /**
         * {@inheritdoc}
         */
        _configureForValues: function () {
            if (this.options.values) {
                this.options.settings.each($.proxy(function (index, element) {
                    var attributeId = element.attributeId,
                        value = this.options.values[attributeId] || '';

                    element.value = value;
                    if (value) {
                        $(element).trigger('change');
                        $(element).prop('disabled', true);
                    }
                    this._configureElement(element);
                }, this));
            }
        }
    });

    return $.awafptc.configurable;
});

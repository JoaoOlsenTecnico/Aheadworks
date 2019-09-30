/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/text'
], function (Text) {
    'use strict';

    return Text.extend({
        defaults: {
            attributes: [],
            optionLabels: '${ $.optionLabelsDataScope }',
            links: {
                value: '${ $.provider }:${ $.dataScope }.${ $.index }',
                record: '${ $.provider }:${ $.dataScope }'
            },
            listens: {
                record: 'prepareOptions'
            }
        },

        /**
         * Initializes observable properties
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super()
                .observe(['record'])
                .observe({'options': []});

            return this;
        },

        /**
         * Prepare product options
         *
         * @param {Object} record
         */
        prepareOptions: function (record) {
            if (record.option) {
                this.options(record.option[this.optionLabels])
            } else {
                this.options([]);
            }
        }
    });
});

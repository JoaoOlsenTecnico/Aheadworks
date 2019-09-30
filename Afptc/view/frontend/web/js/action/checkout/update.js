/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/full-screen-loader'
], function ($, getPaymentInformationAction, totals, fullScreenLoader) {
    'use strict';

    return function () {
        var deferred = $.Deferred();

            totals.isLoading(true);
            getPaymentInformationAction(deferred);
            $.when(deferred).done(function () {
                fullScreenLoader.stopLoader();
                totals.isLoading(false);
            });
    };
});

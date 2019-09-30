/**
 * Copyright 2019 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

var config = {
    paths: {
        uikit: 'Aheadworks_Afptc/js/uikit/uikit'
    },
    shim: {

        uikit : {
            deps: ['jquery']
        }
    },
    config: {
        mixins: {
            'mage/storage': {
                'Aheadworks_Afptc/js/mage/storage-mixin': true
            },
        }
    },
    map: {
        '*': {
            awAfptcConfigurable: 'Aheadworks_Afptc/js/widget/configurable',
            awAfptcPromoRenderer: 'Aheadworks_Afptc/js/widget/promo-renderer'
        }
    }
};
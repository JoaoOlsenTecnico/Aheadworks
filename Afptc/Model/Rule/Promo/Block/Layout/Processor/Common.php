<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor;

/**
 * Class Common
 *
 * @package Aheadworks\Afptc\Model\Label\Block\Layout\Processor
 */
class Common implements LayoutProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($jsLayout, $promoInfoBlock, $placement, $scope)
    {
        $promo = $promoInfoBlock->getPromo();
        $jsLayout['components'] = [
            $scope => [
                'component' => 'Aheadworks_Afptc/js/components/promo/info-link',
                'infoLinkText' => $promo->getPromoOfferInfoText(),
                'popupConfig' => [
                    'header' => $promo->getPromoHeader(),
                    'description' => $promo->getPromoDescription()
                ]
            ]
        ];

        return $jsLayout;
    }
}

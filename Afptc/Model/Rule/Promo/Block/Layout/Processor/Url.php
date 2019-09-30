<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor;

use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url\Resolver as UrlResolver;

/**
 * Class Url
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor
 */
class Url implements LayoutProcessorInterface
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var UrlResolver
     */
    private $urlResolver;

    /**
     * @param ArrayManager $arrayManager
     * @param UrlResolver $urlResolver
     */
    public function __construct(
        ArrayManager $arrayManager,
        UrlResolver $urlResolver
    ) {
        $this->arrayManager = $arrayManager;
        $this->urlResolver = $urlResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function process($jsLayout, $promoInfoBlock, $placement, $scope)
    {
        $promo = $promoInfoBlock->getPromo();
        $component = 'components/' . $scope;
        $jsLayout = $this->arrayManager->merge(
            $component,
            $jsLayout,
            [
                'popupConfig' => [
                    'url' => $this->urlResolver->resolve($promo->getPromoUrl()),
                    'urlText' => $promo->getPromoUrlText()
                ],
            ]
        );

        return $jsLayout;
    }
}

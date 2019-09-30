<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Block;

use Aheadworks\Afptc\Block\Promo\Renderer as PromoRenderer;
use Aheadworks\Afptc\Block\Promo\RendererFactory as PromoRendererFactory;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;

/**
 * Class ListProductPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Block
 */
class ListProductPlugin
{
    /**
     * @var PromoRendererFactory
     */
    private $promoRendererFactory;

    /**
     * @param PromoRendererFactory $promoRendererFactory
     */
    public function __construct(
        PromoRendererFactory $promoRendererFactory
    ) {
        $this->promoRendererFactory = $promoRendererFactory;
    }

    /**
     * Render promo block if product valid
     *
     * @param AbstractProduct $subject
     * @param \Closure $proceed
     * @param Product $product
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetProductDetailsHtml($subject, $proceed, $product)
    {
        $html = $proceed($product);

        /** @var PromoRenderer $promoRenderer */
        $promoRenderer = $this->promoRendererFactory->create(
            [
                'data' => [
                    'placement' => Placement::PRODUCT_LIST,
                    'product' => $product
                ]
            ]
        );
        $html .= $promoRenderer->toHtml();

        return $html;
    }
}

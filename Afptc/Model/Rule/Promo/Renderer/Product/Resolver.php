<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Renderer\Product;

use Aheadworks\Afptc\Model\Source\Rule\Promo\Renderer\Placement;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;

/**
 * Class Resolver
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Renderer\Product
 */
class Resolver
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var array
     */
    private $placementBy = ['registry' => [Placement::PRODUCT_PAGE]];

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Resolve product by placement
     *
     * @param string $placement
     * @return Product|ProductInterface|null
     */
    public function resolveByPlacement($placement)
    {
        $product = null;
        if (in_array($placement, $this->placementBy['registry'])) {
            $product = $this->registry->registry('product');
        }

        return $product;
    }
}

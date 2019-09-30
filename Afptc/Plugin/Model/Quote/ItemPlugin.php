<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote;

use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

/**
 * Class ItemPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Quote
 */
class ItemPlugin
{
    /**
     * Check product representation in item
     *
     * @param Item $subject
     * @param \Closure $proceed
     * @param Product $product
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return bool
     */
    public function aroundRepresentProduct($subject, \Closure $proceed, $product)
    {
        $result = $proceed($product);

        if ($product->getAwAfptcIsPromo() && empty($subject->getAwAfptcIsPromo())) {
            return false;
        }
        if (empty($product->getAwAfptcIsPromo()) && $subject->getAwAfptcIsPromo()) {
            return false;
        }

        return $result;
    }
}

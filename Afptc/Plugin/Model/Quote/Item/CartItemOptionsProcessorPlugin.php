<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote\Item;

use Aheadworks\Afptc\Api\Data\CartItemInterface;
use Magento\Framework\DataObject\Factory as DataObjectFactory;
use Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor;

/**
 * Class CartItemOptionsProcessorPlugin
 * @package Aheadworks\Afptc\Plugin\Model\Quote\Item
 */
class CartItemOptionsProcessorPlugin
{
    /**
     * @var DataObjectFactory
     */
    private $objectFactory;

    /**
     * @param DataObjectFactory $objectFactory
     */
    public function __construct(DataObjectFactory $objectFactory)
    {
        $this->objectFactory = $objectFactory;
    }

    /**
     * Modify request if needed
     *
     * @param CartItemOptionsProcessor $subject
     * @param \Closure $proceed
     * @param $productType
     * @param CartItemInterface $cartItem
     * @return \Magento\Framework\DataObject|float
     */
    public function aroundGetBuyRequest($subject, \Closure $proceed, $productType, $cartItem)
    {
        $buyRequest = $proceed($productType, $cartItem);
        $cartItemExtAttr = $cartItem->getExtensionAttributes();

        if (!$cartItem->getItemId()
            && $cartItemExtAttr && $cartItemExtAttr->getAwAfptcIsPromo()
        ) {
            if (is_numeric($buyRequest)) {
                $buyRequest = $this->objectFactory->create(['qty' => $buyRequest]);
            }
            $buyRequest
                ->setAwAfptcIsPromo($cartItemExtAttr->getAwAfptcIsPromo())
                ->setAwAfptcRules($cartItemExtAttr->getAwAfptcRulesRequest())
                ->setAwAfptcUniqueId($cartItemExtAttr->getAwUniqueId());
        }

        return $buyRequest;
    }
}

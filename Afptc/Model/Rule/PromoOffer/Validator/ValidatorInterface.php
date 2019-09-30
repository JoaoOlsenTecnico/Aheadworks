<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Interface ValidatorInterface
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Validator
 */
interface ValidatorInterface
{
    /**
     * Check if valid rule
     *
     * @param RuleInterface $rule
     * @param Address $address
     * @param AbstractItem|null $quoteItem
     * @return bool
     */
    public function isValidRule($rule, $address, $quoteItem = null);

    /**
     * Check that the card contains regular products (not promo)
     *
     * @param AbstractItem[] $items
     * @return bool
     */
    public function isValidItems($items);
}

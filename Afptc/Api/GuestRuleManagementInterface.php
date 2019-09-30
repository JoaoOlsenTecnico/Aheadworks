<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface GuestRuleManagementInterface
 * @api
 */
interface GuestRuleManagementInterface
{
    /**
     * Retrieve valid metadata rules for auto add products to cart
     * Store ID can be specified when using WEB API
     *
     * @param string $cartId
     * @param int|null $lastQuoteItemId
     * @param int|null $storeId
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[]
     */
    public function getAutoAddMetadataRules($cartId, $storeId = null, $lastQuoteItemId = null);

    /**
     * Retrieve valid metadata rules for display on pop up
     * Store ID can be specified when using WEB API
     *
     * @param string $cartId
     * @param int|null $storeId
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[]
     */
    public function getPopUpMetadataRules($cartId, $storeId = null);

    /**
     * Retrieve valid metadata rules for calculate discount
     * Store ID can be specified when using WEB API
     *
     * @param string $cartId
     * @param int|null $storeId
     * @return \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[]
     */
    public function getDiscountMetadataRules($cartId, $storeId = null);

    /**
     * Check if valid coupon code for cart
     *
     * @param string $couponCode
     * @param int $cartId
     * @param int|null $storeId
     * @return bool|null
     */
    public function isValidCoupon($couponCode, $cartId, $storeId = null);
}

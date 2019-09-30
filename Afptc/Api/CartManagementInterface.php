<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface CartManagementInterface
 * @api
 */
interface CartManagementInterface
{
    /**
     * Add promo products to cart if exist available rules
     *
     * @param int $cartId
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[] $metadataRules
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addPromoProducts($cartId, $metadataRules);

    /**
     * Remove unused promo data from cart
     *
     * @param int|\Magento\Quote\Model\Quote $cart
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeUnusedPromoData($cart);
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface GuestCartManagementInterface
 * @api
 */
interface GuestCartManagementInterface
{
    /**
     * Add promo products to cart if exist available rules
     *
     * @param string $cartId
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[] $metadataRules
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addPromoProducts($cartId, $metadataRules);

    /**
     * Remove unused promo data from cart
     *
     * @param string $cartId
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeUnusedPromoData($cartId);
}

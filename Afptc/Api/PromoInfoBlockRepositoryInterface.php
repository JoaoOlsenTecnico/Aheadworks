<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface PromoInfoBlockRepositoryInterface
 * @api
 */
interface PromoInfoBlockRepositoryInterface
{
    /**
     * Retrieve promo blocks matching the specified criteria
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param int $customerGroupId
     * @return \Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface[]
     */
    public function getList($product, $customerGroupId);
}

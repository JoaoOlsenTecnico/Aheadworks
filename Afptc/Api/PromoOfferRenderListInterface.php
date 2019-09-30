<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface PromoOfferRenderListInterface
 * @api
 */
interface PromoOfferRenderListInterface
{
    /**
     * Collect and retrieve the list of promo offer render info
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleMetadataInterface[] $metadataRules
     * @param int $storeId
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface
     */
    public function getList($metadataRules, $storeId);
}

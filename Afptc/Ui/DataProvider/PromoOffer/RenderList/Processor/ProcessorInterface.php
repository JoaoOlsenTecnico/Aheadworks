<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
interface ProcessorInterface
{
    /**
     * Prepare promo offer render
     *
     * @param PromoOfferRenderInterface $promoOfferRender
     * @param RuleMetadataInterface[] $metadataRules
     */
    public function prepareRender($promoOfferRender, $metadataRules);
}

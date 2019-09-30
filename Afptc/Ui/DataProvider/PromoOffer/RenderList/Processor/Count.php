<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterface;

/**
 * Class Count
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class Count implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        $promoOfferRender->setCount($this->calculateGeneralQtyToGive($promoOfferRender->getRulesConfig()));
    }

    /**
     * Calculate general qty to give
     *
     * @param RuleConfigInterface[] $rulesConfig
     * @return float
     */
    private function calculateGeneralQtyToGive($rulesConfig)
    {
        $count = 0;
        foreach ($rulesConfig as $ruleConfig) {
            $count += $ruleConfig->getQtyToGive();
        }
        return $count;
    }
}

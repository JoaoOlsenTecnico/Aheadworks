<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo;

use Aheadworks\Afptc\Api\PromoInfoBlockRepositoryInterface;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Factory as PromoBlockFactory;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Rule\Loader;

/**
 * Class BlockRepository
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo
 */
class InfoBlockRepository implements PromoInfoBlockRepositoryInterface
{
    /**
     * @var PromoBlockFactory
     */
    private $promoBlockFactory;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @param PromoBlockFactory $promoBlockFactory
     * @param Loader $loader
     */
    public function __construct(
        PromoBlockFactory $promoBlockFactory,
        Loader $loader
    ) {
        $this->promoBlockFactory = $promoBlockFactory;
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($product, $customerGroupId)
    {
        $promoBlockItems = [];
        $ruleProductData = $this->loader->getAvailableRuleDataForProduct($product, $customerGroupId);
        $promoItems = $this->loader->getPromoItems($ruleProductData);

        foreach ($promoItems as $promoItem) {
            $promoBlockItems[] = $this->promoBlockFactory->create($promoItem);
        }

        return $promoBlockItems;
    }
}

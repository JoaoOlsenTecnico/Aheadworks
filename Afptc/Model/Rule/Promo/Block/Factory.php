<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block;

use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterfaceFactory;
use Aheadworks\Afptc\Api\Data\PromoInterface;

/**
 * Class Factory
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block
 */
class Factory
{
    /**
     * @var PromoInfoBlockInterfaceFactory
     */
    private $promoBlockFactory;

    /**
     * @param PromoInfoBlockInterfaceFactory $promoInfoBlockFactory
     */
    public function __construct(
        PromoInfoBlockInterfaceFactory $promoInfoBlockFactory
    ) {
        $this->promoBlockFactory = $promoInfoBlockFactory;
    }

    /**
     * Create promo block
     *
     * @param PromoInterface $promo
     * @return PromoInfoBlockInterface
     */
    public function create($promo)
    {
        /** @var PromoInfoBlockInterface $promoInfoBlockFactory */
        $blockModel = $this->promoBlockFactory->create();
        $blockModel->setPromo($promo);

        return $blockModel;
    }
}

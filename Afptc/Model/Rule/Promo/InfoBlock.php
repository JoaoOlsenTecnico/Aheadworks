<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo;

use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class InfoBlock
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo
 */
class InfoBlock extends AbstractExtensibleObject implements PromoInfoBlockInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPromo()
    {
        return $this->_get(self::PROMO);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromo($promo)
    {
        return $this->setData(self::PROMO, $promo);
    }
}

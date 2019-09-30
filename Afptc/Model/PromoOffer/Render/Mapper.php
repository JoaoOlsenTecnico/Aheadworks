<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\PromoOffer\Render;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;
use Magento\Framework\EntityManager\MapperInterface;

/**
 * Class Mapper
 *
 * @package Aheadworks\Afptc\Model\PromoOffer\Render
 */
class Mapper implements MapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function entityToDatabase($entityType, $data)
    {
        if (isset($data[PromoOfferRenderInterface::ITEMS]) && is_array($data[PromoOfferRenderInterface::ITEMS])) {
            foreach ($data[PromoOfferRenderInterface::ITEMS] as &$item) {
                $item[ProductRenderInterface::OPTION] =  \Zend_Json::decode($item[ProductRenderInterface::OPTION]);
            }
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function databaseToEntity($entityType, $data)
    {
        return $data;
    }
}

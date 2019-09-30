<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\PromoProduct;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResourceModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\AbstractSaveHandler;

/**
 * Class SaveHandler
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Relation\PromoProduct
 */
class SaveHandler extends AbstractSaveHandler
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->initTable(RuleResourceModel::PROMO_PRODUCT_TABLE_NAME);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function process($entity, $arguments = [])
    {
        $this->deleteOldChildEntityById($entity->getRuleId());
        $toInsert = $this->getPromoProducts($entity);
        $this->insertChildEntity($toInsert);

        return $entity;
    }

    /**
     * Retrieve array of promo product data to insert
     *
     * @param RuleInterface $entity
     * @return array
     */
    private function getPromoProducts($entity)
    {
        $promoProductsSku = [];
        foreach ($entity->getPromoProducts() as $promoProduct) {
            $promoProductsSku[] = [
                RuleInterface::RULE_ID => $entity->getRuleId(),
                RulePromoProductInterface::PRODUCT_SKU => $promoProduct->getProductSku(),
                RulePromoProductInterface::OPTION => $promoProduct->getOption(),
                RulePromoProductInterface::POSITION => $promoProduct->getPosition()
            ];
        }
        return $promoProductsSku;
    }
}

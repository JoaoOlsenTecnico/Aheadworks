<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\StockProductPool;
use Magento\Store\Model\Store;
use Aheadworks\Afptc\Model\Metadata\Rule\PromoProductFactory;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataProcessor
 */
class Rule
{
    /**
     * Affected rule ids
     */
    const AFFECTED_RULE_IDS = 'affected_rule_ids';

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @var StockProductPool
     */
    private $stockProductPool;

    /**
     * @var PromoProductFactory
     */
    private $ruleMetadataPromoProductFactory;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param ProductRepositoryInterface $productRepository
     * @param RuleResource $ruleResource
     * @param StockProductPool $stockProductPool
     * @param PromoProductFactory $ruleMetadataPromoProductFactory
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        ProductRepositoryInterface $productRepository,
        RuleResource $ruleResource,
        StockProductPool $stockProductPool,
        PromoProductFactory $ruleMetadataPromoProductFactory
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->productRepository = $productRepository;
        $this->ruleResource = $ruleResource;
        $this->stockProductPool = $stockProductPool;
        $this->ruleMetadataPromoProductFactory = $ruleMetadataPromoProductFactory;
    }

    /**
     * Check all rule data
     *
     * @param RuleInterface[] $rules
     * @return RuleInterface[]
     * @throws LocalizedException
     * @throws \Exception
     */
    public function checkAllRuleData($rules)
    {
        $storeId = Store::DEFAULT_STORE_ID;

        foreach ($rules as $rule) {
            if (!$rule->isActive()) {
                continue;
            }
            $isAnyProductSalable = $this->findAnySalableProduct($rule, $storeId);
            if (!$isAnyProductSalable && $rule->isInStockOfferOnly()) {
                $rule->setIsActive(false);
                $this->ruleRepository->save($rule);
            }
        }

        return $rules;
    }

    /**
     * Check rule data for products
     *
     * @param array $ids
     * @return array
     * @throws LocalizedException
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function checkRuleDataForProducts($ids)
    {
        $ruleIds = $this->ruleResource->getRuleIdsByPromoProducts($ids);
        $storeId = Store::DEFAULT_STORE_ID;
        $affectedRuleIds = [];

        foreach ($ruleIds as $ruleId) {
            $rule = $this->ruleRepository->get($ruleId, $storeId);
            if (!$rule->isActive()) {
                continue;
            }

            $isAnyProductSalable = $this->findAnySalableProduct($rule, $storeId);
            if (!$isAnyProductSalable && $rule->isInStockOfferOnly()) {
                $rule->setIsActive(false);
                $this->ruleRepository->save($rule);
                $affectedRuleIds[] = $rule->getRuleId();
            }
        }

        return $affectedRuleIds;
    }

    /**
     * Check if there is any salable promo product in a rule
     *
     * @param RuleInterface $rule
     * @param int $storeId
     * @return bool
     * @throws \Exception
     */
    public function findAnySalableProduct($rule, $storeId)
    {
        $result = false;
        foreach ($rule->getPromoProducts() as $promoProduct) {
            try {
                $product = $this->productRepository->get($promoProduct->getProductSku(), false, $storeId);
                $ruleMetaDataPromoProduct = $this->convertToRuleMetaDataPromoProduct($promoProduct);
                $stockProduct = $this->stockProductPool->getStockProduct($product->getTypeId());

                $availableQty = $stockProduct->getAvailableQtyForIndex($ruleMetaDataPromoProduct, $storeId);
                if ($availableQty > 0 || !$stockProduct->isManageStockEnabled($ruleMetaDataPromoProduct, $storeId)) {
                    $result = true;
                    break;
                }
            } catch (\Exception $exception) {
            }
        }
        return $result;
    }

    /**
     * Convert promo product to rule meta data promo product
     *
     * @param RulePromoProductInterface $promoProduct
     * @return RuleMetadataPromoProductInterface
     */
    private function convertToRuleMetaDataPromoProduct($promoProduct)
    {
        $ruleMetaDataPromoProduct = $this->ruleMetadataPromoProductFactory->create(
            [
                RuleMetadataPromoProductInterface::PRODUCT_SKU => $promoProduct->getProductSku(),
                RuleMetadataPromoProductInterface::OPTION => $promoProduct->getOption(),
                RuleMetadataPromoProductInterface::QTY => 0,
            ]
        );

        return $ruleMetaDataPromoProduct;
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Store\Model\Store;
use Aheadworks\Afptc\Model\Rule\Condition\Loader as ConditionLoader;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct\DataCollector\Validator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\WebsiteInterface;

/**
 * Class DataCollector
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct
 */
class DataCollector
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ConditionLoader
     */
    private $conditionLoader;

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ConditionLoader $conditionLoader
     * @param RuleRepositoryInterface $ruleRepository
     * @param Validator $validator
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ConditionLoader $conditionLoader,
        RuleRepositoryInterface $ruleRepository,
        Validator $validator
    ) {
        $this->conditionLoader = $conditionLoader;
        $this->storeManager = $storeManager;
        $this->ruleRepository = $ruleRepository;
        $this->validator = $validator;
    }

    /**
     * Prepare rule data for all matching products
     *
     * @param RuleInterface $rule
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareRuleData(RuleInterface $rule)
    {
        $data = [];
        if ($this->validator->isRuleValid($rule)) {
            $websiteIds = $rule->getWebsiteIds();
            $productConditionRule = $this->conditionLoader->loadProductCondition($rule);
            $matchingProducts = $productConditionRule->getMatchingProductIds($websiteIds);
            $data = $this->prepareData($matchingProducts, $rule);
        }

        return $data;
    }

    /**
     * Prepare rule product data for specific product
     *
     * @param RuleInterface $rule
     * @param ProductInterface $product
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareRuleProductData(RuleInterface $rule, ProductInterface $product)
    {
        $data = [];
        if ($this->validator->isInputDataValid($rule, $product)) {
            $productId = $product->getId();
            $matchingProducts = [];

            foreach ($rule->getWebsiteIds() as $ruleWebsiteId) {
                $matchingProducts[$productId][$ruleWebsiteId] = in_array($ruleWebsiteId, $product->getWebsiteIds());
            }
            $data = $this->prepareData($matchingProducts, $rule);
        }

        return $data;
    }

    /**
     * Prepare data
     *
     * @param array $productIds
     * @param RuleInterface $rule
     * @return array
     * @throws NoSuchEntityException
     */
    private function prepareData($productIds, $rule)
    {
        $data = [];
        $customerGroupIds = $rule->getCustomerGroupIds();
        $websiteIds = $rule->getWebsiteIds();

        foreach ($productIds as $productId => $validationByWebsite) {
            foreach ($websiteIds as $websiteId) {
                $stores = $this->getWebsiteStores($websiteId);
                if ($stores && !empty($validationByWebsite[$websiteId])) {
                    /** @var Store $store */
                    foreach ($stores as $store) {
                        $storeId = $store->getStoreId();
                        /** @var RuleInterface $eavRule */
                        $eavRule = $this->ruleRepository->get($rule->getRuleId(), $storeId);

                        foreach ($customerGroupIds as $customerGroupId) {
                            $data[] = [
                                RuleProductInterface::RULE_ID => $eavRule->getRuleId(),
                                RuleProductInterface::FROM_DATE => $eavRule->getFromDate(),
                                RuleProductInterface::TO_DATE => $eavRule->getToDate(),
                                RuleProductInterface::CUSTOMER_GROUP_ID => $customerGroupId,
                                RuleProductInterface::PRODUCT_ID => $productId,
                                RuleProductInterface::PRIORITY => $eavRule->getPriority(),
                                RuleProductInterface::STORE_ID => $storeId,
                                RuleProductInterface::PROMO_OFFER_INFO_TEXT => $eavRule->getPromoOfferInfoText(),
                                RuleProductInterface::PROMO_ON_SALE_LABEL_ID => $eavRule->getPromoOnSaleLabelId(),
                                RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_LARGE
                                => $eavRule->getPromoOnSaleLabelLarge(),
                                RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_MEDIUM
                                => $eavRule->getPromoOnSaleLabelMedium(),
                                RuleProductInterface::PROMO_ON_SALE_LABEL_TEXT_SMALL
                                => $eavRule->getPromoOnSaleLabelSmall(),
                                RuleProductInterface::PROMO_IMAGE => $eavRule->getPromoImage(),
                                RuleProductInterface::PROMO_IMAGE_ALT_TEXT => $eavRule->getPromoImageAltText(),
                                RuleProductInterface::PROMO_HEADER => $eavRule->getPromoHeader(),
                                RuleProductInterface::PROMO_DESCRIPTION => $eavRule->getPromoDescription(),
                                RuleProductInterface::PROMO_URL => $eavRule->getPromoUrl(),
                                RuleProductInterface::PROMO_URL_TEXT => $eavRule->getPromoUrlText()
                            ];
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get stores associated with website
     *
     * @param int $websiteId
     * @return WebsiteInterface|bool
     */
    private function getWebsiteStores($websiteId)
    {
        try {
            return $this->storeManager->getWebsite($websiteId)->getStores();
        } catch (\Exception $exception) {
            return false;
        }
    }
}

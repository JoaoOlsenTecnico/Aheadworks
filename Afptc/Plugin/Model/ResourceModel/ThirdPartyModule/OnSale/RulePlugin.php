<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\ResourceModel\ThirdPartyModule\OnSale;

use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;
use Magento\Framework\DB\Select;

/**
 * Class RulePlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\ResourceModel\ThirdPartyModule\OnSale
 */
class RulePlugin
{
    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @param RuleResource $ruleResource
     */
    public function __construct(RuleResource $ruleResource)
    {
        $this->ruleResource = $ruleResource;
    }

    /**
     * Modify On Sale query
     *
     * @param \Aheadworks\OnSale\Model\ResourceModel\Rule $subject
     * @param \Closure $proceed
     * @param int $productId
     * @param int $customerGroupId
     * @param int $storeId
     * @param string $currentDate
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return Select
     */
    public function aroundGetQueryForSortedRulesForProduct(
        $subject,
        \Closure $proceed,
        $productId,
        $customerGroupId,
        $storeId,
        $currentDate
    ) {
        $onSaleQuery = $proceed($productId, $customerGroupId, $storeId, $currentDate);
        $afptcQuery = $this->ruleResource
            ->getSortedRulesQueryForOnSale($productId, $customerGroupId, $storeId, $currentDate);

        try {
            $select = $this->ruleResource->mergeSortedRuleQueriesForOnSale([$onSaleQuery, $afptcQuery]);
        } catch (\Exception $e) {
            $select = $onSaleQuery;
        }

        return $select;
    }
}

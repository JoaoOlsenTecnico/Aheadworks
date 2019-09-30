<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Rule CRUD interface
 * @api
 */
interface RuleRepositoryInterface
{
    /**
     * Save rule
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleInterface $rule
     * @return \Aheadworks\Afptc\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Afptc\Api\Data\RuleInterface $rule);

    /**
     * Retrieve rule by ID
     *
     * @param int $ruleId
     * @param int|null $storeId
     * @return \Aheadworks\Afptc\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($ruleId, $storeId = null);

    /**
     * Retrieve rules matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param int|null $storeId
     * @return \Aheadworks\Afptc\Api\Data\RuleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria, $storeId = null);

    /**
     * Delete rule
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleInterface $rule
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Afptc\Api\Data\RuleInterface $rule);

    /**
     * Delete rule by ID
     *
     * @param int $ruleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ruleId);
}

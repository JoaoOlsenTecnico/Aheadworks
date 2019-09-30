<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Coupon;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\SalesRule\Api\Data\RuleInterface as SalesRuleInterface;
use Magento\SalesRule\Api\Data\RuleInterfaceFactory;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;

/**
 * Class SalesRuleManager
 *
 * @package Aheadworks\Afptc\Model\Rule\Coupon
 */
class SalesRuleManager
{
    /**
     * @var RuleInterfaceFactory
     */
    private $ruleFactory;

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @param RuleInterfaceFactory $ruleFactory
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(
        RuleInterfaceFactory $ruleFactory,
        RuleRepositoryInterface $ruleRepository
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * Save sales rule
     *
     * @param RuleInterface $afptcRule
     * @param int|null $salesRuleId
     * @return SalesRuleInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveSalesRule($afptcRule, $salesRuleId = null)
    {
        /** @var SalesRuleInterface $salesRule */
        $salesRule = $this->ruleFactory->create();
        $salesRule->setIsActive($this->isRuleActive($afptcRule));
        if ($salesRuleId) {
            $salesRule->setRuleId($salesRuleId);
        }
        $salesRule
            ->setCustomerGroupIds($afptcRule->getCustomerGroupIds())
            ->setFromDate($this->prepareDate($afptcRule->getFromDate()))
            ->setToDate($this->prepareDate($afptcRule->getToDate()))
            ->setWebsiteIds($afptcRule->getWebsiteIds())
            ->setCouponType(SalesRuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
            ->setName($afptcRule->getName())
            ->setDescription($this->prepareDescription($afptcRule))
            ->setDiscountAmount(0)
            ->setDiscountQty(0)
            ->setDiscountStep(0)
            ->setUsesPerCoupon(0)
            ->setUsesPerCustomer(0)
            ->setTimesUsed(0)
            ->setSortOrder(0)
            ->setUseAutoGeneration(false)
            ->setStopRulesProcessing(true)
            ->setApplyToShipping(true)
            ->setSimpleAction(SalesRuleInterface::DISCOUNT_ACTION_BY_PERCENT);
        return $this->ruleRepository->save($salesRule);
    }

    /**
     * Delete sales rule
     *
     * @param int $salesRuleId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteSalesRule($salesRuleId)
    {
        return $this->ruleRepository->deleteById($salesRuleId);
    }

    /**
     * Prepare rule description
     *
     * @param RuleInterface $afptcRule
     * @return string
     */
    private function prepareDescription($afptcRule)
    {
        $beginningText = __(
            'This coupon has been auto-created by Add Free Product To Cart extension (%1). ' .
            'Do not modify it\'s conditions and actions.',
            'Rule # '. $afptcRule->getRuleId() . ', <' . $afptcRule->getName() . '>'
        );
        $description = $beginningText . '   ' . $afptcRule->getDescription();
        return $description;
    }

    /**
     * Prepare date
     *
     * @param string $date
     * @return string
     */
    private function prepareDate($date)
    {
        return $date ? : '';
    }

    /**
     * Check if sales rule is active
     *
     * @param RuleInterface $afptcRule
     * @return bool
     */
    private function isRuleActive($afptcRule)
    {
        return $afptcRule->isActive()
            && $afptcRule->getScenario() == Scenario::COUPON;
    }
}

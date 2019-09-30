<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Observer\Magento\SalesRule;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Aheadworks\Afptc\Model\Rule\Coupon\CouponManager;
use Aheadworks\Afptc\Model\Rule\Coupon\SalesRuleManager;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;

/**
 * Class SaveCoupon
 *
 * @package Aheadworks\Afptc\Observer\Magento\SalesRule
 */
class SaveCoupon implements ObserverInterface
{
    /**
     * @var SalesRuleManager
     */
    private $salesRuleManager;

    /**
     * @var CouponManager
     */
    private $couponManager;

    /**
     * @param SalesRuleManager $salesRuleManager
     * @param CouponManager $couponManager
     */
    public function __construct(
        SalesRuleManager $salesRuleManager,
        CouponManager $couponManager
    ) {
        $this->salesRuleManager = $salesRuleManager;
        $this->couponManager = $couponManager;
    }

    /**
     * Create sales rule and coupon
     *
     * @param Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var RuleInterface $afptcRule */
        $afptcRule = $observer->getEvent()->getEntity();

        $salesRuleId = null;
        $couponId = $afptcRule->getCouponId();
        if ($afptcRule->getScenario() == Scenario::COUPON || $couponId) {
            if ($couponId) {
                $salesRuleId = $this->couponManager->getSalesRuleId($couponId);
            }
            $salesRule = $this->salesRuleManager->saveSalesRule($afptcRule, $salesRuleId);
            $coupon = $this->couponManager->saveCoupon($afptcRule, $salesRule);
            $afptcRule->setCouponId($coupon->getCouponId());
        }

        return $this;
    }
}

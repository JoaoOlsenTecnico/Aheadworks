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

/**
 * Class DeleteCoupon
 *
 * @package Aheadworks\Afptc\Observer\Magento\SalesRule
 */
class DeleteCoupon implements ObserverInterface
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
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var RuleInterface $afptcRule */
        $afptcRule = $observer->getEvent()->getEntity();

        if ($couponId = $afptcRule->getCouponId()) {
            $salesRuleId = $this->couponManager->getSalesRuleId($couponId);
            $this->salesRuleManager->deleteSalesRule($salesRuleId);
        }

        return $this;
    }
}

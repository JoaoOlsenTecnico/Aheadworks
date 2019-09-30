<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Coupon;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\SalesRule\Api\Data\RuleInterface as SalesRuleInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Api\Data\CouponInterfaceFactory;
use Magento\SalesRule\Api\CouponRepositoryInterface;

/**
 * Class CouponManager
 *
 * @package Aheadworks\Afptc\Model\Rule\Coupon
 */
class CouponManager
{
    /**
     * @var CouponInterfaceFactory
     */
    private $couponFactory;

    /**
     * @var CouponRepositoryInterface
     */
    private $couponRepository;

    /**
     * @param CouponInterfaceFactory $couponFactory
     * @param CouponRepositoryInterface $couponRepository
     */
    public function __construct(
        CouponInterfaceFactory $couponFactory,
        CouponRepositoryInterface $couponRepository
    ) {
        $this->couponFactory = $couponFactory;
        $this->couponRepository = $couponRepository;
    }

    /**
     * Save coupon
     *
     * @param RuleInterface $afptcRule
     * @param SalesRuleInterface $salesRule
     * @return CouponInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveCoupon($afptcRule, $salesRule)
    {
        /** @var CouponInterface $coupon */
        $coupon = $this->couponFactory->create();
        if ($couponId = $afptcRule->getCouponId()) {
            $coupon->setCouponId($couponId);
        }
        $coupon
            ->setRuleId($salesRule->getRuleId())
            ->setIsPrimary(true)
            ->setCode($afptcRule->getCouponCode())
            ->setType(CouponInterface::TYPE_MANUAL);

        return $this->couponRepository->save($coupon);
    }

    /**
     * Get sales rule ID
     *
     * @param int $couponId
     * @return int|null
     */
    public function getSalesRuleId($couponId)
    {
        try {
            $coupon = $this->getCouponById($couponId);
            $salesRuleId = $coupon->getRuleId();
        } catch (\Exception $e) {
            $salesRuleId = null;
        }

        return $salesRuleId;
    }

    /**
     * Get coupon by ID
     *
     * @param int $couponId
     * @return CouponInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCouponById($couponId)
    {
        return $this->couponRepository->getById($couponId);
    }
}

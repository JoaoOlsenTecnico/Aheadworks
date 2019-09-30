<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Quote;

use Magento\Quote\Api\CouponManagementInterface;
use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CouponManagementPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Quote
 */
class CouponManagementPlugin
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param CartManagementInterface $cartManagement
     * @param RuleManagementInterface $ruleManagement
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        RuleManagementInterface $ruleManagement,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->cartManagement = $cartManagement;
        $this->ruleManagement = $ruleManagement;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Check added coupon or deleted and add automatic products to the cart if needed
     *
     * @param CouponManagementInterface $subject
     * @param callable $proceed
     * @param int $cartId
     * @param string $couponCodeFromRequest
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundSet($subject, callable $proceed, $cartId, $couponCodeFromRequest)
    {
        /** @var Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $couponCodeBefore = $quote->getCouponCode();
        $isProcessed = true;
        if (empty($couponCodeBefore) && !empty($couponCodeFromRequest)) {
            $isValidCoupon = $this->ruleManagement->isValidCoupon($couponCodeFromRequest, $quote->getId());
            $isProcessed = $isValidCoupon === null ? true : $isValidCoupon;
        }

        if ($isProcessed) {
            $result = $proceed($cartId, $couponCodeFromRequest);
            $couponCodeAfter = $quote->getCouponCode();
            if (empty($couponCodeBefore) && !empty($couponCodeAfter)) {
                $metadataRules = $this->ruleManagement->getAutoAddMetadataRules($quote->getId());
                $this->cartManagement->addPromoProducts($quote->getId(), $metadataRules);
            }
        } else {
            throw new NoSuchEntityException(__('The coupon code "%1" is not valid.', $couponCodeFromRequest));
        }

        return $result;
    }
}

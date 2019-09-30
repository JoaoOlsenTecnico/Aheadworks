<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Magento\Checkout\Model\Session as CheckoutSession;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Quote\Model\Quote;

/**
 * Class Coupon
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class Coupon implements ProcessorInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @param CheckoutSession $checkoutSession
     * @param RuleManagementInterface $ruleManagement
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        RuleManagementInterface $ruleManagement
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->ruleManagement = $ruleManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        $isCouponUsed = false;
        /** @var Quote $quote */
        $quote = $this->checkoutSession->getQuote();
        $couponCode = $quote->getCouponCode();

        if ($couponCode) {
            $isCouponUsed = $this->ruleManagement->isValidCoupon($couponCode, $quote->getId());
        }

        $promoOfferRender->setIsCouponUsed($isCouponUsed);
    }
}

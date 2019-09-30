<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Controller;

use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Checkout\Controller\Cart\CouponPost;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class CartCouponPostPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Controller
 */
class CartCouponPostPlugin
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
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param CartManagementInterface $cartManagement
     * @param CheckoutSession $checkoutSession
     * @param RuleManagementInterface $ruleManagement
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        CheckoutSession $checkoutSession,
        RuleManagementInterface $ruleManagement,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->cartManagement = $cartManagement;
        $this->checkoutSession = $checkoutSession;
        $this->ruleManagement = $ruleManagement;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Check added coupon or deleted and add automatic products to the cart if needed
     *
     * @param CouponPost $subject
     * @param \Closure $proceed
     * @return Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundExecute($subject, \Closure $proceed)
    {
        $quote = $this->checkoutSession->getQuote();
        $couponCodeBefore = $quote->getCouponCode();
        $couponCodeFromRequest = trim($subject->getRequest()->getParam('coupon_code'));
        $isProcessed = true;
        if (empty($couponCodeBefore) && !empty($couponCodeFromRequest)) {
            $isValidCoupon = $this->ruleManagement->isValidCoupon($couponCodeFromRequest, $quote->getId());
            $isProcessed = $isValidCoupon === null ? true : $isValidCoupon;
        }

        if ($isProcessed) {
            $resultRedirect = $proceed();
            $couponCodeAfter = $quote->getCouponCode();
            if (empty($couponCodeBefore) && !empty($couponCodeAfter)) {
                $metadataRules = $this->ruleManagement->getAutoAddMetadataRules($quote->getId());
                $this->cartManagement->addPromoProducts($quote->getId(), $metadataRules);
            }
        } else {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setRefererUrl();
            $this->messageManager->addErrorMessage(__('The coupon code "%1" is not valid.', $couponCodeFromRequest));
        }

        return $resultRedirect;
    }
}

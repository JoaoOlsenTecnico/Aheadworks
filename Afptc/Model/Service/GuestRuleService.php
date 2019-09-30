<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Service;

use Aheadworks\Afptc\Api\RuleManagementInterface;
use Aheadworks\Afptc\Api\GuestRuleManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestRuleService
 *
 * @package Aheadworks\Afptc\Model\Service
 */
class GuestRuleService implements GuestRuleManagementInterface
{
    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param RuleManagementInterface $ruleManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        RuleManagementInterface $ruleManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->ruleManagement = $ruleManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoAddMetadataRules($cartId, $storeId = null, $lastQuoteItemId = null)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->ruleManagement->getAutoAddMetadataRules($quoteIdMask->getQuoteId(), $storeId, $lastQuoteItemId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPopUpMetadataRules($cartId, $storeId = null)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->ruleManagement->getPopUpMetadataRules($quoteIdMask->getQuoteId(), $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountMetadataRules($cartId, $storeId = null)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->ruleManagement->getDiscountMetadataRules($quoteIdMask->getQuoteId(), $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function isValidCoupon($couponCode, $cartId, $storeId = null)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->ruleManagement->isValidCoupon($couponCode, $quoteIdMask->getQuoteId(), $storeId);
    }
}

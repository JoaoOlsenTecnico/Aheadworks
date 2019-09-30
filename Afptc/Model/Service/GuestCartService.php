<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Service;

use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\GuestCartManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestCartService
 *
 * @package Aheadworks\Afptc\Model\Service
 */
class GuestCartService implements GuestCartManagementInterface
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param CartManagementInterface $cartManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->cartManagement = $cartManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function addPromoProducts($cartId, $metadataRules)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $this->cartManagement->addPromoProducts($quoteIdMask->getQuoteId(), $metadataRules);
    }

    /**
     * {@inheritdoc}
     */
    public function removeUnusedPromoData($cartId)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $this->cartManagement->removeUnusedPromoData($quoteIdMask->getQuoteId());
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model\Service;

use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\Data\CartInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;

/**
 * Class QuoteRepositoryPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model\Service
 */
class QuoteRepositoryPlugin
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param CartManagementInterface $cartManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        LoggerInterface $logger
    ) {
        $this->cartManagement = $cartManagement;
        $this->logger = $logger;
    }

    /**
     * Remove unused promo products from cart
     *
     * @param QuoteRepository $subject
     * @param \Closure $proceed
     * @param CartInterface $quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function aroundSave($subject, \Closure $proceed, $quote)
    {
        $proceed($quote);
        $quoteId = $quote->getId();
        if ($quoteId) {
            try {
                $this->cartManagement->removeUnusedPromoData($quoteId);
            } catch (LocalizedException $e) {
                $this->logger->error($e);
            }
        }
    }
}

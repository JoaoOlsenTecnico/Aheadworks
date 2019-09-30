<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Service;

use Aheadworks\Afptc\Api\RuleManagementInterface;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Manager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Modifier;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Model\Cart\Area\Resolver as CartAreaResolver;

/**
 * Class RuleService
 *
 * @package Aheadworks\Afptc\Model\Service
 */
class RuleService implements RuleManagementInterface
{
    /**
     * @var Manager
     */
    private $ruleManager;

    /**
     * @var Modifier
     */
    private $addressModifier;

    /**
     * @var CartAreaResolver
     */
    private $cartAreaResolver;

    /**
     * @param Manager $ruleManager
     * @param Modifier $addressModifier
     * @param CartAreaResolver $cartAreaResolver
     */
    public function __construct(
        Manager $ruleManager,
        Modifier $addressModifier,
        CartAreaResolver $cartAreaResolver
    ) {
        $this->ruleManager = $ruleManager;
        $this->addressModifier = $addressModifier;
        $this->cartAreaResolver = $cartAreaResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoAddMetadataRules($cartId, $lastQuoteItemId = null, $storeId = null)
    {
        $mtdRules = [];
        try {
            list($storeId, $customerGroupId, $items, $address) = $this->getCartParams($cartId, $storeId);
            $lastQuoteItem = null === $lastQuoteItemId
                ? null
                : $lastQuoteItem = $this->findCartItemById($items, $lastQuoteItemId);
            if (false !== $lastQuoteItem) {
                $mtdRules = $this->ruleManager
                    ->getAutoAddMetadataRules($storeId, $customerGroupId, $address, $items, $lastQuoteItem);
            }
        } catch (NoSuchEntityException $e) {
        }

        return $mtdRules;
    }

    /**
     * {@inheritdoc}
     */
    public function getPopUpMetadataRules($cartId, $storeId = null)
    {
        $mtdRules = [];
        try {
            list($storeId, $customerGroupId, $items, $address) = $this->getCartParams($cartId, $storeId);
            $mtdRules = $this->ruleManager->getPopUpMetadataRules($storeId, $customerGroupId, $address, $items);
        } catch (NoSuchEntityException $e) {
        }

        return $mtdRules;
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountMetadataRules($cartId, $storeId = null)
    {
        $mtdRules = [];
        try {
            list($storeId, $customerGroupId, $items, $address) = $this->getCartParams($cartId, $storeId);
            $mtdRules = $this->ruleManager->getDiscountMetadataRules($storeId, $customerGroupId, $address, $items);
        } catch (NoSuchEntityException $e) {
        }

        return $mtdRules;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidCoupon($couponCode, $cartId, $storeId = null)
    {
        $isValid = false;
        try {
            list($storeId, $customerGroupId, $items, $address) = $this->getCartParams($cartId, $storeId);
            $address->setCouponCode($couponCode);
            $isValid = $this->ruleManager->isValidCoupon($couponCode, $storeId, $customerGroupId, $address, $items);
        } catch (NoSuchEntityException $e) {
        }

        return $isValid;
    }

    /**
     * Retrieve cart params
     *
     * @param int $cartId
     * @param int $storeId
     * @return array
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getCartParams($cartId, $storeId = null)
    {
        $quote = $this->cartAreaResolver->resolve($cartId);
        if ($storeId) {
            $quote->setStoreId($storeId);
        } else {
            $storeId = $quote->getStore()->getStoreId();
        }

        $customerGroupId = $quote->getCustomerGroupId();
        $address = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
        $address = $this->addressModifier->modify($address, $quote);
        $items = $quote->getAllItems() ? : [];

        return [$storeId, $customerGroupId, $items, $address];
    }

    /**
     * Find cart item by id
     *
     * @param AbstractItem[] $items
     * @param int $findQuoteItemId
     * @return AbstractItem|bool
     */
    private function findCartItemById($items, $findQuoteItemId)
    {
        foreach ($items as $item) {
            if ($findQuoteItemId == $item->getItemId()) {
                return $item;
            }
        }
        return false;
    }
}

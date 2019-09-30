<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule;
use Aheadworks\Afptc\Model\Rule\Listing\Builder;
use Aheadworks\Afptc\Model\Source\Rule\HowToOfferType;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Processor\Pool;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Manager
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer
 */
class Manager
{
    /**
     * @var Builder
     */
    private $ruleBuilder;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ToMetadataRule
     */
    private $converter;

    /**
     * @param Builder $ruleBuilder
     * @param Validator $validator
     * @param ToMetadataRule $converter
     */
    public function __construct(
        Builder $ruleBuilder,
        Validator $validator,
        ToMetadataRule $converter
    ) {
        $this->ruleBuilder = $ruleBuilder;
        $this->validator = $validator;
        $this->converter = $converter;
    }

    /**
     * Retrieve valid metadata rules for auto add products to cart
     *
     * @param int $storeId
     * @param int $customerGroupId
     * @param Address $address
     * @param AbstractItem[] $items
     * @param AbstractItem|null $lastQuoteItem
     * @return RuleMetadataInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getAutoAddMetadataRules($storeId, $customerGroupId, $address, $items, $lastQuoteItem = null)
    {
        $this->ruleBuilder
            ->getSearchCriteriaBuilder()
            ->addFilter(RuleInterface::HOW_TO_OFFER, HowToOfferType::AUTO_ADDING);
        $rules = $this->ruleBuilder->getActiveRules($storeId, $customerGroupId);

        $validRules = $this->validator->getValidRules($rules, $address, $items, $lastQuoteItem);
        return $this->converter->convert($validRules, $items, Pool::AUTO_ADD_PROCESSOR);
    }

    /**
     * Retrieve valid metadata rules for display on pop up
     *
     * @param int $storeId
     * @param int $customerGroupId
     * @param Address $address
     * @param AbstractItem[] $items
     * @return RuleMetadataInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getPopUpMetadataRules($storeId, $customerGroupId, $address, $items)
    {
        $rules = $this->ruleBuilder->getActiveRules($storeId, $customerGroupId);
        $validRules = $this->validator->getValidRules($rules, $address, $items);
        return $this->converter->convert($validRules, $items, Pool::POPUP_PROCESSOR);
    }

    /**
     * Retrieve valid metadata rules for calculate discount
     *
     * @param int $storeId
     * @param int $customerGroupId
     * @param Address $address
     * @param AbstractItem[] $items
     * @return RuleMetadataInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function getDiscountMetadataRules($storeId, $customerGroupId, $address, $items)
    {
        $rules = $this->ruleBuilder->getActiveRules($storeId, $customerGroupId);
        $validRules = $this->validator->getValidRules($rules, $address, $items);
        return $this->converter->convert($validRules, $items, Pool::DISCOUNT);
    }

    /**
     * Check if valid coupon code
     *
     * @param string $couponCode
     * @param int $storeId
     * @param int $customerGroupId
     * @param Address $address
     * @param AbstractItem[] $items
     * @return bool|null
     * @throws \Exception
     */
    public function isValidCoupon($couponCode, $storeId, $customerGroupId, $address, $items)
    {
        $isValid = null;
        $rules = $this->ruleBuilder->getActiveRules($storeId, $customerGroupId);
        if ($this->isExistsCoupon($couponCode, $rules)) {
            $validRules = $this->validator->getValidRules($rules, $address, $items);
            $isValid = $this->isExistsCoupon($couponCode, $validRules);
        }

        return $isValid;
    }

    /**
     * Check if exist coupon among the rules
     *
     * @param string $couponCode
     * @param RuleInterface[] $rules
     * @return bool
     */
    public function isExistsCoupon($couponCode, $rules)
    {
        $isExists = false;
        foreach ($rules as $rule) {
            if ($rule->getScenario() == Scenario::COUPON
                && strtolower($rule->getCouponCode()) == strtolower($couponCode)
            ) {
                $isExists = true;
                break;
            }
        }
        return $isExists;
    }
}

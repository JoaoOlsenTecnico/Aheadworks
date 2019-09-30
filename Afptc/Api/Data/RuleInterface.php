<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

/**
 * Interface RuleInterface
 * @api
 */
interface RuleInterface extends PromoInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const RULE_ID = 'rule_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const IS_ACTIVE = 'active';
    const WEBSITE_IDS = 'website_ids';
    const CUSTOMER_GROUP_IDS = 'customer_group_ids';
    const FROM_DATE = 'from_date';
    const TO_DATE = 'to_date';
    const PRIORITY = 'priority';
    const STOP_RULES_PROCESSING = 'stop_rules_processing';
    const IN_STOCK_OFFER_ONLY = 'in_stock_offer_only';
    const SCENARIO = 'scenario';
    const CART_CONDITIONS = 'cart_conditions';
    const SIMPLE_ACTION = 'simple_action';
    const SIMPLE_ACTION_N = 'simple_action_n';
    const QTY_TO_GIVE = 'qty_to_give';
    const PROMO_PRODUCTS = 'promo_products';
    const DISCOUNT_AMOUNT = 'discount_amount';
    const DISCOUNT_TYPE = 'discount_type';
    const COUPON_CODE = 'coupon_code';
    const COUPON_ID = 'coupon_id';
    const HOW_TO_OFFER = 'how_to_offer';
    const POPUP_HEADER_TEXT = 'popup_header_text';
    const STORE_ID = 'store_id';
    /**#@-*/

    /**
     * Get Rule ID
     *
     * @return int
     */
    public function getRuleId();

    /**
     * Set Rule ID
     *
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get is active
     *
     * @return bool
     */
    public function isActive();

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Get website IDs
     *
     * @return int[]
     */
    public function getWebsiteIds();

    /**
     * Set website IDs
     *
     * @param int[] $websiteIds
     * @return $this
     */
    public function setWebsiteIds($websiteIds);

    /**
     * Get allowed customer groups for rule
     *
     * @return int[]
     */
    public function getCustomerGroupIds();

    /**
     * Set allowed customer groups for rule
     *
     * @param int[] $customerGroupIds
     * @return $this
     */
    public function setCustomerGroupIds($customerGroupIds);

    /**
     * Get from date
     *
     * @return string|null
     */
    public function getFromDate();

    /**
     * Set from date
     *
     * @param string|null $fromDate
     * @return $this
     */
    public function setFromDate($fromDate);

    /**
     * Get to date
     *
     * @return string|null
     */
    public function getToDate();

    /**
     * Set to date
     *
     * @param string|null $toDate
     * @return $this
     */
    public function setToDate($toDate);

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority();

    /**
     * Set priority
     *
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority);

    /**
     * Whether to stop rule processing
     *
     * @return bool
     */
    public function isStopRulesProcessing();

    /**
     * Set whether to stop rule processing
     *
     * @param bool $stopRulesProcessing
     * @return $this
     */
    public function setStopRulesProcessing($stopRulesProcessing);

    /**
     * Get is in stock offer only
     *
     * @return bool
     */
    public function isInStockOfferOnly();

    /**
     * Set is active
     *
     * @param bool $inStockOfferOnly
     * @return $this
     */
    public function setIsInStockOfferOnly($inStockOfferOnly);

    /**
     * Get scenario
     *
     * @return string
     */
    public function getScenario();

    /**
     * Set scenario
     *
     * @param string $scenario
     * @return $this
     */
    public function setScenario($scenario);

    /**
     * Get cart conditions
     *
     * @return \Aheadworks\Afptc\Api\Data\ConditionInterface
     */
    public function getCartConditions();

    /**
     * Set cart conditions
     *
     * @param \Aheadworks\Afptc\Api\Data\ConditionInterface $cartConditions
     * @return $this
     */
    public function setCartConditions($cartConditions);

    /**
     * Get simple action
     *
     * @return string
     */
    public function getSimpleAction();

    /**
     * Set simple action
     *
     * @param string $simpleAction
     * @return $this
     */
    public function setSimpleAction($simpleAction);

    /**
     * Get simple action N
     *
     * @return int
     */
    public function getSimpleActionN();

    /**
     * Set simple action N
     *
     * @param int $simpleActionN
     * @return $this
     */
    public function setSimpleActionN($simpleActionN);

    /**
     * Get qty to give
     *
     * @return int
     */
    public function getQtyToGive();

    /**
     * Set qty to give
     *
     * @param int $qtyToGive
     * @return $this
     */
    public function setQtyToGive($qtyToGive);

    /**
     * Get promo products
     *
     * @return \Aheadworks\Afptc\Api\Data\RulePromoProductInterface[]
     */
    public function getPromoProducts();

    /**
     * Set promo products
     *
     * @param \Aheadworks\Afptc\Api\Data\RulePromoProductInterface[] $promoProducts
     * @return $this
     */
    public function setPromoProducts($promoProducts);

    /**
     * Get discount amount
     *
     * @return float
     */
    public function getDiscountAmount();

    /**
     * Set discount amount
     *
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get discount type
     *
     * @return string
     */
    public function getDiscountType();

    /**
     * Set discount type
     *
     * @param string $discountType
     * @return $this
     */
    public function setDiscountType($discountType);

    /**
     * Get coupon code
     *
     * @return string
     */
    public function getCouponCode();

    /**
     * Get coupon ID
     *
     * @return int|null
     */
    public function getCouponId();

    /**
     * Set coupon ID
     *
     * @param int|null $couponId
     * @return $this
     */
    public function setCouponId($couponId);

    /**
     * Set coupon code
     *
     * @param string $couponCode
     * @return $this
     */
    public function setCouponCode($couponCode);

    /**
     * Get how to offer
     *
     * @return string
     */
    public function getHowToOffer();

    /**
     * Set how to offer
     *
     * @param string $howToOffer
     * @return $this
     */
    public function setHowToOffer($howToOffer);

    /**
     * Get popup header text
     *
     * @return string
     */
    public function getPopupHeaderText();

    /**
     * Set popup header text
     *
     * @param string $popupHeaderText
     * @return $this
     */
    public function setPopupHeaderText($popupHeaderText);

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\RuleExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RuleExtensionInterface $extensionAttributes
    );
}

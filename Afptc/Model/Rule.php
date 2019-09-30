<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule as ResourceRule;
use Aheadworks\Afptc\Model\Rule\Processor;
use Aheadworks\Afptc\Model\Rule\Validator;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model
 */
class Rule extends AbstractModel implements RuleInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'aw_afptc_rule';

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Processor $processor
     * @param Validator $validator
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Processor $processor,
        Validator $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->validator = $validator;
        $this->processor = $processor;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceRule::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getRuleId()
    {
        return $this->getData(self::RULE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRuleId($ruleId)
    {
        return $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsiteIds()
    {
        return $this->getData(self::WEBSITE_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setWebsiteIds($websiteIds)
    {
        return $this->setData(self::WEBSITE_IDS, $websiteIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroupIds()
    {
        return $this->getData(self::CUSTOMER_GROUP_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGroupIds($customerGroupIds)
    {
        return $this->setData(self::CUSTOMER_GROUP_IDS, $customerGroupIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getFromDate()
    {
        return $this->getData(self::FROM_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFromDate($fromDate)
    {
        return $this->setData(self::FROM_DATE, $fromDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getToDate()
    {
        return $this->getData(self::TO_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setToDate($toDate)
    {
        return $this->setData(self::TO_DATE, $toDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->getData(self::PRIORITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority($priority)
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function isStopRulesProcessing()
    {
        return $this->getData(self::STOP_RULES_PROCESSING);
    }

    /**
     * {@inheritdoc}
     */
    public function setStopRulesProcessing($stopRulesProcessing)
    {
        return $this->setData(self::STOP_RULES_PROCESSING, $stopRulesProcessing);
    }

    /**
     * {@inheritdoc}
     */
    public function isInStockOfferOnly()
    {
        return $this->getData(self::IN_STOCK_OFFER_ONLY);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsInStockOfferOnly($inStockOfferOnly)
    {
        return $this->setData(self::IN_STOCK_OFFER_ONLY, $inStockOfferOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function getScenario()
    {
        return $this->getData(self::SCENARIO);
    }

    /**
     * {@inheritdoc}
     */
    public function setScenario($scenario)
    {
        return $this->setData(self::SCENARIO, $scenario);
    }

    /**
     * {@inheritdoc}
     */
    public function getCartConditions()
    {
        return $this->getData(self::CART_CONDITIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCartConditions($cartConditions)
    {
        return $this->setData(self::CART_CONDITIONS, $cartConditions);
    }

    /**
     * {@inheritdoc}
     */
    public function getSimpleAction()
    {
        return $this->getData(self::SIMPLE_ACTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setSimpleAction($simpleAction)
    {
        return $this->setData(self::SIMPLE_ACTION, $simpleAction);
    }

    /**
     * {@inheritdoc}
     */
    public function getSimpleActionN()
    {
        return $this->getData(self::SIMPLE_ACTION_N);
    }

    /**
     * {@inheritdoc}
     */
    public function setSimpleActionN($simpleActionN)
    {
        return $this->setData(self::SIMPLE_ACTION_N, $simpleActionN);
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToGive()
    {
        return $this->getData(self::QTY_TO_GIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyToGive($qtyToGive)
    {
        return $this->setData(self::QTY_TO_GIVE, $qtyToGive);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoProducts()
    {
        return $this->getData(self::PROMO_PRODUCTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoProducts($promoProducts)
    {
        return $this->setData(self::PROMO_PRODUCTS, $promoProducts);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountAmount()
    {
        return $this->getData(self::DISCOUNT_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountAmount($discountAmount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discountAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountType()
    {
        return $this->getData(self::DISCOUNT_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountType($discountType)
    {
        return $this->setData(self::DISCOUNT_TYPE, $discountType);
    }

    /**
     * {@inheritdoc}
     */
    public function getCouponCode()
    {
        return $this->getData(self::COUPON_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCouponCode($couponCode)
    {
        return $this->setData(self::COUPON_CODE, $couponCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getCouponId()
    {
        return $this->getData(self::COUPON_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCouponId($couponId)
    {
        return $this->setData(self::COUPON_ID, $couponId);
    }

    /**
     * {@inheritdoc}
     */
    public function getHowToOffer()
    {
        return $this->getData(self::HOW_TO_OFFER);
    }

    /**
     * {@inheritdoc}
     */
    public function setHowToOffer($howToOffer)
    {
        return $this->setData(self::HOW_TO_OFFER, $howToOffer);
    }

    /**
     * {@inheritdoc}
     */
    public function getPopupHeaderText()
    {
        return $this->getData(self::POPUP_HEADER_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPopupHeaderText($popupHeaderText)
    {
        return $this->setData(self::POPUP_HEADER_TEXT, $popupHeaderText);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOfferInfoText($promoOfferInfoText)
    {
        return $this->setData(self::PROMO_OFFER_INFO_TEXT, $promoOfferInfoText);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOfferInfoText()
    {
        return $this->getData(self::PROMO_OFFER_INFO_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelId($promoOnSaleLabelId)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_ID, $promoOnSaleLabelId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelId()
    {
        return $this->getData(self::PROMO_ON_SALE_LABEL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelLarge($promoOnSaleLabelLarge)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_LARGE, $promoOnSaleLabelLarge);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelLarge()
    {
        return $this->getData(self::PROMO_ON_SALE_LABEL_TEXT_LARGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelMedium($promoOnSaleLabelMedium)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_MEDIUM, $promoOnSaleLabelMedium);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelMedium()
    {
        return $this->getData(self::PROMO_ON_SALE_LABEL_TEXT_MEDIUM);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelSmall($promoOnSaleLabelSmall)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_SMALL, $promoOnSaleLabelSmall);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelSmall()
    {
        return $this->getData(self::PROMO_ON_SALE_LABEL_TEXT_SMALL);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoImage()
    {
        return $this->getData(self::PROMO_IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoImage($promoImage)
    {
        return $this->setData(self::PROMO_IMAGE, $promoImage);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoImageAltText()
    {
        return $this->getData(self::PROMO_IMAGE_ALT_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoImageAltText($promoImageAltText)
    {
        return $this->setData(self::PROMO_IMAGE_ALT_TEXT, $promoImageAltText);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoHeader()
    {
        return $this->getData(self::PROMO_HEADER);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoHeader($promoHeader)
    {
        return $this->setData(self::PROMO_HEADER, $promoHeader);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoDescription()
    {
        return $this->getData(self::PROMO_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoDescription($promoDescription)
    {
        return $this->setData(self::PROMO_DESCRIPTION, $promoDescription);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoUrl()
    {
        return $this->getData(self::PROMO_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoUrl($promoUrl)
    {
        return $this->setData(self::PROMO_URL, $promoUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoUrlText()
    {
        return $this->getData(self::PROMO_URL_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoUrlText($promoUrlText)
    {
        return $this->setData(self::PROMO_URL_TEXT, $promoUrlText);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\RuleExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $this->processor->prepareDataBeforeSave($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad()
    {
        $this->processor->prepareDataAfterLoad($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getValidationRulesBeforeSave()
    {
        return $this->validator;
    }
}

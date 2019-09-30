<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\PromoOffer;

use Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Render
 *
 * @package Aheadworks\Afptc\Model\PromoOffer
 */
class Render extends AbstractExtensibleObject implements PromoOfferRenderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWebsiteId()
    {
        return $this->_get(self::WEBSITE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return $this->_get(self::COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCount($count)
    {
        return $this->setData(self::COUNT, $count);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * {@inheritdoc}
     */
    public function getRulesConfig()
    {
        return $this->_get(self::RULES_CONFIG);
    }

    /**
     * {@inheritdoc}
     */
    public function setRulesConfig($rulesConfig)
    {
        return $this->setData(self::RULES_CONFIG, $rulesConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderText()
    {
        return $this->_get(self::HEADER_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaderText($headerText)
    {
        return $this->setData(self::HEADER_TEXT, $headerText);
    }

    /**
     * {@inheritdoc}
     */
    public function isQuantityNoticeActive()
    {
        return $this->_get(self::IS_QUANTITY_NOTICE_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsQuantityNoticeActive($isQuantityNoticeActive)
    {
        return $this->setData(self::IS_QUANTITY_NOTICE_ACTIVE, $isQuantityNoticeActive);
    }

    /**
     * {@inheritdoc}
     */
    public function isCouponUsed()
    {
        return $this->_get(self::IS_COUPON_USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsCouponUsed($isCouponUsed)
    {
        return $this->setData(self::IS_COUPON_USED, $isCouponUsed);
    }

    /**
     * {@inheritdoc}
     */
    public function getPageRoutesToDisplayPopup()
    {
        return $this->_get(self::PAGE_ROUTES_TO_DISPLAY_POPUP);
    }

    /**
     * {@inheritdoc}
     */
    public function setPageRoutesToDisplayPopup($pageRoutesList)
    {
        return $this->setData(self::PAGE_ROUTES_TO_DISPLAY_POPUP, $pageRoutesList);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRenderExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\PromoOffer\Render;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Product
 *
 * @package Aheadworks\Afptc\Model\PromoOffer\Render
 */
class Product extends AbstractExtensibleObject implements ProductRenderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->_get(self::KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function getChecked()
    {
        return $this->_get(self::CHECKED);
    }

    /**
     * {@inheritdoc}
     */
    public function setChecked($checked)
    {
        return $this->setData(self::CHECKED, $checked);
    }

    /**
     * {@inheritdoc}
     */
    public function getQty()
    {
        return $this->_get(self::QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->_get(self::NAME);
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
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($productType)
    {
        return $this->setData(self::TYPE, $productType);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsSalable()
    {
        return $this->_get(self::IS_SALABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSalable($isSalable)
    {
        return $this->setData(self::IS_SALABLE, $isSalable);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
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
    public function getCurrencyCode()
    {
        return $this->_get(self::CURRENCY_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getRuleId()
    {
        return $this->_get(self::RULE_ID);
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
    public function getOption()
    {
        return $this->_get(self::OPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option)
    {
        return $this->setData(self::OPTION, $option);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceInfo()
    {
        return $this->_get(self::PRICE_INFO);
    }

    /**
     * {@inheritdoc}
     */
    public function setPriceInfo($priceInfo)
    {
        return $this->setData(self::PRICE_INFO, $priceInfo);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->_get(self::IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsOptionBlockHidden()
    {
        return $this->_get(self::IS_OPTION_BLOCK_HIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsOptionBlockHidden($isOptionBlockHidden)
    {
        return $this->setData(self::IS_OPTION_BLOCK_HIDDEN, $isOptionBlockHidden);
    }

    /**
     * {@inheritdoc}
     */
    public function getToggleOptionLinkText()
    {
        return $this->_get(self::TOGGLE_OPTION_LINK_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setToggleOptionLinkText($toggleOptionLinkText)
    {
        return $this->setData(self::TOGGLE_OPTION_LINK_TEXT, $toggleOptionLinkText);
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
        \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}

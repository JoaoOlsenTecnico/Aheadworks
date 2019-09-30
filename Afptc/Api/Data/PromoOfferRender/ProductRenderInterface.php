<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data\PromoOfferRender;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductRendererInterface
 * @api
 */
interface ProductRenderInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const KEY = 'key';
    const CHECKED = 'checked';
    const QTY = 'qty';
    const SKU = 'sku';
    const NAME = 'name';
    const TYPE = 'type';
    const IS_SALABLE = 'is_salable';
    const URL = 'url';
    const STORE_ID = 'store_id';
    const CURRENCY_CODE = 'currency_code';
    const RULE_ID = 'rule_id';
    const OPTION = 'option';
    const PRICE_INFO = 'price_info';
    const IMAGE = 'image';
    const IS_OPTION_BLOCK_HIDDEN = 'is_option_block_hidden';
    const TOGGLE_OPTION_LINK_TEXT = 'toggle_option_link_text';
    /**#@-*/

    /**
     * Get key
     *
     * @return string
     */
    public function getKey();

    /**
     * Set key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Get checked
     *
     * @return bool
     */
    public function getChecked();

    /**
     * Set checked
     *
     * @param bool $checked
     * @return $this
     */
    public function setChecked($checked);

    /**
     * Get qty
     *
     * @return float
     */
    public function getQty();

    /**
     * Set qty
     *
     * @param float $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Get product name
     *
     * @return string
     */
    public function getName();

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get product type
     *
     * @return string
     */
    public function getType();

    /**
     * Set product type
     *
     * @param string $productType
     * @return $this
     */
    public function setType($productType);

    /**
     * Get is salable
     *
     * @return string
     */
    public function getIsSalable();

    /**
     * Set is salable
     *
     * @param string $isSalable
     * @return $this
     */
    public function setIsSalable($isSalable);

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Get currency code
     *
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Set currency code
     *
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Get rule id
     *
     * @return string
     */
    public function getRuleId();

    /**
     * Set currency code
     *
     * @param string $ruleId
     * @return $this
     */
    public function setRuleId($ruleId);

    /**
     * Get option
     *
     * @return string
     */
    public function getOption();

    /**
     * Set option
     *
     * @param string $option
     * @return $this
     */
    public function setOption($option);

    /**
     * Get price info
     *
     * @return \Magento\Catalog\Api\Data\ProductRender\PriceInfoInterface
     */
    public function getPriceInfo();

    /**
     * Set price info
     *
     * @param \Magento\Catalog\Api\Data\ProductRender\PriceInfoInterface $priceInfo
     * @return $this
     */
    public function setPriceInfo($priceInfo);

    /**
     * Get image
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageInterface
     */
    public function getImage();

    /**
     * Set image
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRender\ImageInterface $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Get is option block hidden
     *
     * @return bool
     */
    public function getIsOptionBlockHidden();

    /**
     * Set is option block hidden
     *
     * @param bool $isOptionBlockHidden
     * @return $this
     */
    public function setIsOptionBlockHidden($isOptionBlockHidden);

    /**
     * Get toggle option link text
     *
     * @return string
     */
    public function getToggleOptionLinkText();

    /**
     * Set toggle option link text
     *
     * @param string $toggleOptionLinkText
     * @return $this
     */
    public function setToggleOptionLinkText($toggleOptionLinkText);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderExtensionInterface $extensionAttributes
    );
}

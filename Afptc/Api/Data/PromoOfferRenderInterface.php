<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface PromoOfferRenderInterface
 * @api
 */
interface PromoOfferRenderInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const WEBSITE_ID = 'website_id';
    const COUNT = 'count';
    const ITEMS = 'items';
    const RULES_CONFIG = 'rules_config';
    const HEADER_TEXT = 'header_text';
    const IS_QUANTITY_NOTICE_ACTIVE = 'quantity_notice_active';
    const IS_COUPON_USED = 'coupon_used';
    const PAGE_ROUTES_TO_DISPLAY_POPUP = 'page_routes_to_display_popup';
    /**#@-*/

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId);

    /**
     * Get count
     *
     * @return int
     */
    public function getCount();

    /**
     * Set count
     *
     * @param int $count
     * @return $this
     */
    public function setCount($count);

    /**
     * Get items
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface[] $items
     * @return $this
     */
    public function setItems($items);

    /**
     * Get rules config
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterface[]
     */
    public function getRulesConfig();

    /**
     * Set rules config
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRenderInterface[] $rulesConfig
     * @return $this
     */
    public function setRulesConfig($rulesConfig);

    /**
     * Get header text
     *
     * @return string
     */
    public function getHeaderText();

    /**
     * Set header text
     *
     * @param string $headerText
     * @return $this
     */
    public function setHeaderText($headerText);

    /**
     * Get is quantity notice active
     *
     * @return bool
     */
    public function isQuantityNoticeActive();

    /**
     * Set is quantity notice active
     *
     * @param bool $isQuantityNoticeActive
     * @return $this
     */
    public function setIsQuantityNoticeActive($isQuantityNoticeActive);

    /**
     * Get is afpct coupon used
     *
     * @return bool
     */
    public function isCouponUsed();

    /**
     * Set is afptc coupon used
     *
     * @param bool $isCouponUsed
     * @return $this
     */
    public function setIsCouponUsed($isCouponUsed);

    /**
     * Get page routes where to display popup
     *
     * @return array
     */
    public function getPageRoutesToDisplayPopup();

    /**
     * Set pages routes where to display popup
     *
     * @param array $pageRoutesList
     * @return $this
     */
    public function setPageRoutesToDisplayPopup($pageRoutesList);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRenderExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRenderExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRenderExtensionInterface $extensionAttributes
    );
}

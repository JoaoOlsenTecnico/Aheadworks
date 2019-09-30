<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Aheadworks\Afptc\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    const XML_PATH_GENERAL_DEFAULT_OFFER_POPUP_TITLE = 'aw_afptc/general/default_offer_popup_title';
    const XML_PATH_GENERAL_IS_OPTION_BLOCK_HIDDEN = 'aw_afptc/general/is_option_block_hidden';
    const XML_PATH_GENERAL_WHERE_TO_DISPLAY_POPUP_TYPE = 'aw_afptc/general/where_to_display_popup_type';
    const XML_PATH_GENERAL_SUBTOTAL_VALIDATION_TYPE = 'aw_afptc/general/subtotal_validation_type';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get default offer popup title
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultOfferPopupTitle($storeId = null) : string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_DEFAULT_OFFER_POPUP_TITLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get default offer popup title
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isOptionBlockHidden($storeId = null) : bool
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_IS_OPTION_BLOCK_HIDDEN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get where to display popup type
     *
     * @param int|null $storeId
     * @return string
     */
    public function getWhereToDisplayPopupType($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_WHERE_TO_DISPLAY_POPUP_TYPE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get subtotal validation type
     *
     * @param int|null $websiteId
     * @return string
     */
    public function getSubtotalValidationType($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_SUBTOTAL_VALIDATION_TYPE,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct;

/**
 * Interface RuleIndexerDataInterface
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Rule\Indexer\RuleProduct
 */
interface RuleProductInterface
{
    /**#@+
     * Constants for keys of indexer fields.
     */
    const RULE_PRODUCT_ID = 'rule_product_id';
    const RULE_ID = 'rule_id';
    const FROM_DATE = 'from_date';
    const TO_DATE = 'to_date';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    const PRODUCT_ID = 'product_id';
    const PRIORITY = 'priority';
    const STORE_ID = 'store_id';
    const PROMO_OFFER_INFO_TEXT = 'promo_offer_info_text';
    const PROMO_ON_SALE_LABEL_ID = 'promo_on_sale_label_id';
    const PROMO_ON_SALE_LABEL_TEXT_LARGE = 'promo_on_sale_label_text_large';
    const PROMO_ON_SALE_LABEL_TEXT_MEDIUM = 'promo_on_sale_label_text_medium';
    const PROMO_ON_SALE_LABEL_TEXT_SMALL = 'promo_on_sale_label_text_small';
    const PROMO_IMAGE = 'promo_image';
    const PROMO_IMAGE_ALT_TEXT = 'promo_image_alt_text';
    const PROMO_HEADER = 'promo_header';
    const PROMO_DESCRIPTION = 'promo_description';
    const PROMO_URL = 'promo_url';
    const PROMO_URL_TEXT = 'promo_url_text';
    /**#@-*/
}

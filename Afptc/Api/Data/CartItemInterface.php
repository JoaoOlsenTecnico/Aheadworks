<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Quote\Api\Data\CartItemInterface as QuoteCartItemInterfaceInterface;

/**
 * Interface CartItemInterface
 * @api
 */
interface CartItemInterface extends QuoteCartItemInterfaceInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AW_AFPTC_RULE_IDS = 'aw_afptc_rule_ids';
    const AW_AFPTC_PERCENT = 'aw_afptc_percent';
    const AW_AFPTC_QTY = 'aw_afptc_qty';
    const AW_AFPTC_IS_PROMO = 'aw_afptc_is_promo';
    const AW_AFPTC_AMOUNT = 'aw_afptc_amount';
    const BASE_AW_AFPTC_AMOUNT = 'base_aw_afptc_amount';
    const AW_AFPTC_RULES = 'aw_afptc_rules';
    const AW_AFPTC_RULES_REQUEST = 'aw_afptc_rules_request';
    /**#@-*/
}

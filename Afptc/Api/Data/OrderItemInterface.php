<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Sales\Api\Data\OrderItemInterface as SalesOrderItemInterface;

/**
 * Interface OrderItemInterface
 * @api
 */
interface OrderItemInterface extends SalesOrderItemInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AW_AFPTC_RULE_IDS = 'aw_afptc_rule_ids';
    const AW_AFPTC_PERCENT = 'aw_afptc_percent';
    const AW_AFPTC_QTY = 'aw_afptc_qty';
    const AW_AFPTC_QTY_INVOICED = 'aw_afptc_qty_invoiced';
    const AW_AFPTC_QTY_REFUNDED = 'aw_afptc_qty_refunded';
    const AW_AFPTC_IS_PROMO = 'aw_afptc_is_promo';
    const AW_AFPTC_AMOUNT = 'aw_afptc_amount';
    const BASE_AW_AFPTC_AMOUNT = 'base_aw_afptc_amount';
    const AW_AFPTC_INVOICED = 'aw_afptc_invoiced';
    const BASE_AW_AFPTC_INVOICED = 'base_aw_afptc_invoiced';
    const AW_AFPTC_REFUNDED = 'aw_afptc_refunded';
    const BASE_AW_AFPTC_REFUNDED = 'base_aw_afptc_refunded';
    /**#@-*/
}

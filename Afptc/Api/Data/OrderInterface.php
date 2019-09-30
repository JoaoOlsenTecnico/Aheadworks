<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Sales\Api\Data\OrderInterface as SalesOrderInterface;

/**
 * Interface OrderInterface
 * @api
 */
interface OrderInterface extends SalesOrderInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AW_AFPTC_AMOUNT = 'aw_afptc_amount';
    const BASE_AW_AFPTC_AMOUNT = 'base_aw_afptc_amount';
    const AW_AFPTC_INVOICED = 'aw_afptc_invoiced';
    const BASE_AW_AFPTC_INVOICED = 'base_aw_afptc_invoiced';
    const AW_AFPTC_REFUNDED = 'aw_afptc_refunded';
    const BASE_AW_AFPTC_REFUNDED = 'base_aw_afptc_refunded';
    const AW_AFPTC_USES_COUPON = 'aw_afptc_uses_coupon';
    /**#@-*/
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Quote\Api\Data\CartInterface as QuoteCartInterfaceInterface;

/**
 * Interface CartInterface
 * @api
 */
interface CartInterface extends QuoteCartInterfaceInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AW_AFPTC_AMOUNT = 'aw_afptc_amount';
    const BASE_AW_AFPTC_AMOUNT = 'base_aw_afptc_amount';
    const AW_AFPTC_USES_COUPON = 'aw_afptc_uses_coupon';
    /**#@-*/
}

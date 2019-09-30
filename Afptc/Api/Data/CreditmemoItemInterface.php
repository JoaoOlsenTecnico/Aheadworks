<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Sales\Api\Data\CreditmemoInterface as SalesCreditmemoItemInterface;

/**
 * Interface CreditmemoItemInterface
 * @api
 */
interface CreditmemoItemInterface extends SalesCreditmemoItemInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const AW_AFPTC_AMOUNT = 'aw_afptc_amount';
    const BASE_AW_AFPTC_AMOUNT = 'base_aw_afptc_amount';
    /**#@-*/
}

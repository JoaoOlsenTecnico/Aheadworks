<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

/**
 * Interface CartItemRuleInterface
 *
 * @package Aheadworks\Afptc\Api\Data
 */
interface CartItemRuleInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const RULE_ID = 'rule_id';
    const QTY = 'qty';
    /**#@-*/

    /**
     * Get Rule ID
     *
     * @return int
     */
    public function getRuleId();

    /**
     * Set Rule ID
     *
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId);

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
}

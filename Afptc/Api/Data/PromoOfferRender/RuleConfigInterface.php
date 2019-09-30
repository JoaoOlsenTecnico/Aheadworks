<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data\PromoOfferRender;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface RuleConfigInterface
 * @api
 */
interface RuleConfigInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const RULE_ID = 'rule_id';
    const QTY_TO_GIVE = 'qty_to_give';
    /**#@-*/

    /**
     * Get rule id
     *
     * @return int
     */
    public function getRuleId();

    /**
     * Set rule id
     *
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId);

    /**
     * Get qty to give
     *
     * @return float
     */
    public function getQtyToGive();

    /**
     * Set qty to give
     *
     * @param float $qtyToGive
     * @return $this
     */
    public function setQtyToGive($qtyToGive);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigExtensionInterface $extensionAttributes
    );
}

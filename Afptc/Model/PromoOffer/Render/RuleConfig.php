<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\PromoOffer\Render;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class RuleConfig
 *
 * @package Aheadworks\Afptc\Model\PromoOffer\Render
 */
class RuleConfig extends AbstractExtensibleObject implements RuleConfigInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRuleId()
    {
        return $this->_get(self::RULE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRuleId($ruleId)
    {
        return $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToGive()
    {
        return $this->_get(self::QTY_TO_GIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyToGive($qtyToGive)
    {
        return $this->setData(self::QTY_TO_GIVE, $qtyToGive);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\PromoOfferRender\RuleConfigExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}

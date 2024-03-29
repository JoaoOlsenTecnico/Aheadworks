<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule;

use Aheadworks\Afptc\Api\Data\ConditionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Condition
 *
 * @package Aheadworks\Afptc\Model\Rule
 */
class Condition extends AbstractExtensibleObject implements ConditionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions()
    {
        return $this->_get(self::CONDITIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function setConditions(array $conditions = null)
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregator()
    {
        return $this->_get(self::AGGREGATOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setAggregator($aggregator)
    {
        return $this->setData(self::AGGREGATOR, $aggregator);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->_get(self::OPERATOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        return $this->setData(self::OPERATOR, $operator);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->_get(self::ATTRIBUTE);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        return $this->setData(self::ATTRIBUTE, $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getValueType()
    {
        return $this->_get(self::VALUE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setValueType($valueType)
    {
        return $this->setData(self::VALUE_TYPE, $valueType);
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
        \Aheadworks\Afptc\Api\Data\ConditionExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}

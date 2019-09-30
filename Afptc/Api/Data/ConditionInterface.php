<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Condition interface
 * @api
 */
interface ConditionInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TYPE = 'type';
    const CONDITIONS = 'conditions';
    const AGGREGATOR = 'aggregator';
    const OPERATOR = 'operator';
    const ATTRIBUTE = 'attribute';
    const VALUE = 'value';
    const VALUE_TYPE = 'value_type';
    /**#@-*/

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get conditions
     *
     * @return \Aheadworks\Afptc\Api\Data\ConditionInterface[]|null
     */
    public function getConditions();

    /**
     * Set conditions
     *
     * @param \Aheadworks\Afptc\Api\Data\ConditionInterface[]|null $conditions
     * @return $this
     */
    public function setConditions(array $conditions = null);

    /**
     * Get aggregator
     *
     * @return string|null
     */
    public function getAggregator();

    /**
     * Set aggregator
     *
     * @param string $aggregator
     * @return $this
     */
    public function setAggregator($aggregator);

    /**
     * Get operator
     *
     * @return string|null
     */
    public function getOperator();

    /**
     * Set operator
     *
     * @param string $operator
     * @return $this
     */
    public function setOperator($operator);

    /**
     * Get attribute
     *
     * @return string|null
     */
    public function getAttribute();

    /**
     * Set attribute
     *
     * @param string $attribute
     * @return $this
     */
    public function setAttribute($attribute);

    /**
     * Get value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get value type
     *
     * @return string
     */
    public function getValueType();

    /**
     * Set value type
     *
     * @param string $valueType
     * @return $this
     */
    public function setValueType($valueType);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Afptc\Api\Data\ConditionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Afptc\Api\Data\ConditionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Afptc\Api\Data\ConditionExtensionInterface $extensionAttributes
    );
}

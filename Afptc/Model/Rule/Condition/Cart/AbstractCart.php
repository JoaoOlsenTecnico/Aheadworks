<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart;

use Magento\Rule\Model\Condition\Combine as ConditionCombine;
use Magento\Rule\Model\Action\Collection as ActionCollection;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory as ConditionCombineFactory;
use Magento\SalesRule\Model\Rule\Condition\Product\CombineFactory as ConditionProductCombineFactory;
use Magento\Rule\Model\AbstractModel;

/**
 * Class AbstractCart
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart
 */
class AbstractCart extends AbstractModel
{
    /**
     * @var ConditionCombineFactory
     */
    protected $condCombineFactory;

    /**
     * @var ConditionProductCombineFactory
     */
    protected $condProdCombineFactory;

    /**
     * Retrieve rule combine conditions instance
     *
     * @return ConditionCombine
     */
    public function getConditionsInstance()
    {
        return $this->condCombineFactory->create();
    }

    /**
     * Retrieve rule actions collection instance
     *
     * @return ActionCollection|\Magento\SalesRule\Model\Rule\Condition\Product\Combine
     */
    public function getActionsInstance()
    {
        return $this->condProdCombineFactory->create();
    }

    /**
     * Validate by entity ID
     *
     * @param int $entityId
     * @return mixed
     */
    public function validateByEntityId($entityId)
    {
        return $this->getConditions()->validateByEntityId($entityId);
    }
}

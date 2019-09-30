<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule;

use Aheadworks\Afptc\Model\Rule\Condition\Cart\AbstractCart;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\CombineFactory as ConditionCombineFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Combine;
use Magento\SalesRule\Model\Rule\Condition\Product\CombineFactory as ConditionProductCombineFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\BuyXGetY\Form as BuyXGetYForm;

/**
 * Class BuyXGetY
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule
 */
class BuyXGetY extends AbstractCart
{
    /**
     * Condition prefix
     */
    const CONDITION_PREFIX = 'buy_x';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param ConditionCombineFactory $condCombineFactory
     * @param ConditionProductCombineFactory $condProdCombineFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        ConditionCombineFactory $condCombineFactory,
        ConditionProductCombineFactory $condProdCombineFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $localeDate, null, null, $data);
        $this->condCombineFactory = $condCombineFactory;
        $this->condProdCombineFactory = $condProdCombineFactory;
    }

    /**
     * Reset rule combine conditions
     *
     * @param Combine|null $conditions
     * @return $this
     */
    protected function _resetConditions($conditions = null)
    {
        parent::_resetConditions($conditions);
        $this->getConditions($conditions)
            ->setId('1')
            ->setPrefix(BuyXGetYForm::CONDITION_FIELD_NAME);
        return $this;
    }
}

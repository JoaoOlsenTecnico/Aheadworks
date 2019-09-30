<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart\Product;

use Magento\SalesRule\Model\Rule\Condition\Product\Subselect as SalesRuleSubselect;
use Magento\Rule\Model\Condition\Context;
use Magento\SalesRule\Model\Rule\Condition\Product;

/**
 * Class Subselect
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart\Product
 */
class Subselect extends SalesRuleSubselect
{
    use OptionsList;

    /**
     * @param Context $context
     * @param Product $ruleConditionProduct
     * @param array $data
     */
    public function __construct(
        Context $context,
        Product $ruleConditionProduct,
        array $data = []
    ) {
        parent::__construct($context, $ruleConditionProduct, $data);
        $this->setType(Subselect::class)->setValue(null);
    }
}

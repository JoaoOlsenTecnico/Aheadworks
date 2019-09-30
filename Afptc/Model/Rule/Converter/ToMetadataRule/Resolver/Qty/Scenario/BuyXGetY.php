<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario;

use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\ScenarioInterface;
use Aheadworks\Afptc\Model\Source\Rule\SimpleAction;
use Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario\BuyXGetY\Validator as BuyXGetYValidator;

/**
 * Class BuyXGetY
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty\Scenario
 */
class BuyXGetY implements ScenarioInterface
{
    /**
     * @var BuyXGetYValidator
     */
    private $validator;

    /**
     * @param BuyXGetYValidator $validator
     */
    public function __construct(
        BuyXGetYValidator $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToDiscountByRule($rule, $items)
    {
        $qty = 0;
        foreach ($items as $item) {
            if (!$this->validator->isValidItem($item, $rule)) {
                continue;
            }

            if (in_array($rule->getSimpleAction(), [SimpleAction::EVERY, SimpleAction::FOR_EACH_N])) {
                $qty += $item->getTotalQty();
            } elseif ($rule->getSimpleAction() == SimpleAction::ONLY_ONE_OF) {
                $qty = 1;
                break;
            }
        }
        if ($rule->getSimpleAction() == SimpleAction::FOR_EACH_N) {
            $qty = floor($qty / $rule->getSimpleActionN());
        }

        return $qty;
    }
}

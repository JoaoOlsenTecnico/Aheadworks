<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class DiscountAmount
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class DiscountAmount implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        if (isset($data[RuleInterface::DISCOUNT_AMOUNT])) {
            $data[RuleInterface::DISCOUNT_AMOUNT] = $data[RuleInterface::DISCOUNT_AMOUNT] * 1;
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        return $meta;
    }
}

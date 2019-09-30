<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class SimpleAction
 *
 * @package Aheadworks\Afptc\Model\Source\Rule
 */
class SimpleAction implements OptionSourceInterface
{
    /**#@+
     * Rule actions
     */
    const EVERY = 'every';
    const ONLY_ONE_OF = 'only_one_of';
    const FOR_EACH_N = 'for_each_n';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ONLY_ONE_OF, 'label' => __('First time')],
            ['value' => self::EVERY, 'label' => __('Every time')],
            ['value' => self::FOR_EACH_N, 'label' => __('Every Nth time')],
        ];
    }
}

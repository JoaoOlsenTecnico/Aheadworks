<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Rule;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 *
 * @package Aheadworks\Afptc\Model\Source\Rule
 */
class Status implements OptionSourceInterface
{
    /**#@+
     * Rule statuses
     */
    const DISABLED = 0;
    const ENABLED = 1;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DISABLED, 'label' => __('Disabled')],
            ['value' => self::ENABLED, 'label' => __('Enabled')],
        ];
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor;

use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor
 */
interface ProcessorInterface
{
    /**
     * Prepare entity data before save
     *
     * @param CartItemInterface $item
     * @return CartItemInterface
     */
    public function prepareDataBeforeSave($item);

    /**
     * Prepare entity data after load
     *
     * @param CartItemInterface $item
     * @return CartItemInterface
     */
    public function prepareDataAfterLoad($item);
}

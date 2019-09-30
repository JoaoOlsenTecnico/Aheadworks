<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Processor;

use Magento\Quote\Model\Quote\Address;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
interface ProcessorInterface
{
    /**
     * Process address
     *
     * @param Address $address
     * @param array $data
     * @return array
     */
    public function process($address, $data);
}

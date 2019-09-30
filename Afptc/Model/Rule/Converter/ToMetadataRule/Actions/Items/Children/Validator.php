<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Children;

use \Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Validator
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Actions\Items\Children
 */
class Validator
{
    /**
     * @var array
     */
    private $validationByTypeMap;

    /**
     * @param array $validationByTypeMap
     */
    public function __construct(
        array $validationByTypeMap = []
    ) {
        $this->validationByTypeMap = $validationByTypeMap;
    }

    /**
     * Check if validation is required
     *
     * @param AbstractItem $item
     * @return bool
     */
    public function isValidationRequired($item)
    {
        $type = $item->getProduct()->getTypeId();

        return isset($this->validationByTypeMap[$type]) ? (bool)$this->validationByTypeMap[$type] : true;
    }
}

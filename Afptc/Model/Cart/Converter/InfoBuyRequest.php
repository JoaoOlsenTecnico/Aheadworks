<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Converter;

use Magento\Framework\DataObject;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterface;
use Aheadworks\Afptc\Api\Data\CartItemRuleInterfaceFactory;

/**
 * Class InfoBuyRequest
 *
 * @package Aheadworks\Afptc\Model\Cart\Converter
 */
class InfoBuyRequest
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var CartItemRuleInterfaceFactory
     */
    private $cartItemRuleFactory;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param CartItemRuleInterfaceFactory $cartItemRuleFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        CartItemRuleInterfaceFactory $cartItemRuleFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->cartItemRuleFactory = $cartItemRuleFactory;
    }

    /**
     * Convert data from array to data model if needed
     *
     * @param array $dataArray
     * @return DataObject[]
     */
    public function convertToDataModel($dataArray)
    {
        $result = [];
        if ($dataArray && is_array($dataArray)) {
            foreach ($dataArray as $dataItem) {
                if ($dataItem instanceof CartItemRuleInterface) {
                    $result[] = $dataItem;
                } else {
                    /** @var CartItemRuleInterface $object */
                    $cartItemObject = $this->cartItemRuleFactory->create();
                    $this->dataObjectHelper->populateWithArray(
                        $cartItemObject,
                        $dataItem,
                        CartItemRuleInterface::class
                    );
                    $result[] = $cartItemObject;
                }
            }
        }

        return $result;
    }
}

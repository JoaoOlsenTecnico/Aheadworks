<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Metadata\Rule;

use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterface;
use Aheadworks\Afptc\Api\Data\RuleMetadataPromoProductInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class PromoProductFactory
 *
 * @package Aheadworks\Afptc\Model\Metadata\Rule
 */
class PromoProductFactory
{
    /**
     * @var RuleMetadataPromoProductInterfaceFactory
     */
    private $promoProductFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param RuleMetadataPromoProductInterfaceFactory $promoProductFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RuleMetadataPromoProductInterfaceFactory $promoProductFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->promoProductFactory = $promoProductFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Create promo product object for rule metadata
     *
     * @param array $data
     * @return RuleMetadataPromoProductInterface
     */
    public function create($data)
    {
        /** @var RuleMetadataPromoProductInterface $ruleMetadataObject */
        $promoProductObject = $this->promoProductFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $promoProductObject,
            $data,
            RuleMetadataPromoProductInterface::class
        );

        return $promoProductObject;
    }
}

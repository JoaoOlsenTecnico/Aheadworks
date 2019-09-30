<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Processor\PromoProduct;

use Magento\Quote\Api\Data\ProductOptionInterfaceFactory;
use Magento\Quote\Api\Data\ProductOptionInterface;
use Aheadworks\Afptc\Api\Data\RulePromoProductInterface;
use Aheadworks\Afptc\Model\Rule as RuleModel;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Option
 *
 * @package Aheadworks\Afptc\Model\Rule\Processor\PromoProduct
 */
class Option
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ProductOptionInterfaceFactory
     */
    private $productOptionFactory;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param ProductOptionInterfaceFactory $productOptionFactory
     * @param Json $serializer
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        ProductOptionInterfaceFactory $productOptionFactory,
        Json $serializer
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->productOptionFactory = $productOptionFactory;
        $this->serializer = $serializer;
    }

    /**
     * Check product conditions data before save
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function beforeSave($rule)
    {
        /** @var RulePromoProductInterface $promoProduct */
        foreach ($rule->getPromoProducts() as &$promoProduct) {
            if (!empty($promoProduct->getOption())) {
                $optionArray = $this->dataObjectProcessor->buildOutputDataArray(
                    $promoProduct->getOption(),
                    ProductOptionInterface::class
                );
                $promoProduct->setOption($this->serializer->serialize($optionArray));
            }
        }

        return $rule;
    }

    /**
     * Check product conditions data after load
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function afterLoad($rule)
    {
        $preparedPromoProducts = [];
        /** @var RulePromoProductInterface $promoProduct */
        foreach ($rule->getPromoProducts() as $promoProduct) {
            if ($promoProduct instanceof RulePromoProductInterface) {
                $optionObject = $this->convertOptionToObject($promoProduct->getOption());
                $promoProduct->setOption($optionObject);
            } else {
                $optionObject = $this->convertOptionToObject($promoProduct[RulePromoProductInterface::OPTION]);
                $promoProduct[RulePromoProductInterface::OPTION] = $optionObject;
            }
            $preparedPromoProducts[] = $promoProduct;
        }
        $rule->setPromoProducts($preparedPromoProducts);

        return $rule;
    }

    /**
     * Convert option to object
     *
     * @param string $option
     * @return ProductOptionInterface|null
     */
    private function convertOptionToObject($option)
    {
        $optionObject = null;
        if (!empty($option)) {
            $optionArray = $this->serializer->unserialize($option);
            /** @var ProductOptionInterface $option */
            $optionObject = $this->productOptionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $optionObject,
                $optionArray,
                ProductOptionInterface::class
            );
        }
        return $optionObject;
    }
}

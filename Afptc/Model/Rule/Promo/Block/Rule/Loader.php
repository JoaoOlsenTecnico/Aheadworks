<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Rule;

use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Afptc\Api\Data\PromoInterface;
use Aheadworks\Afptc\Api\Data\PromoInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Loader
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Rule
 */
class Loader
{
    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var PromoInterfaceFactory
     */
    private $promoFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param RuleResource $ruleResource
     * @param DateTime $dateTime
     * @param PromoInterfaceFactory $promoFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RuleResource $ruleResource,
        DateTime $dateTime,
        PromoInterfaceFactory $promoFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->ruleResource = $ruleResource;
        $this->dateTime = $dateTime;
        $this->promoFactory = $promoFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Retrieve available rule data for product
     *
     * @param Product|ProductInterface $product
     * @param int $customerGroupId
     * @return array
     */
    public function getAvailableRuleDataForProduct($product, $customerGroupId)
    {
        $productId = $product->getId();
        $storeId = $product->getStoreId();
        $currentDate = $this->dateTime->gmtDate(StdlibDateTime::DATE_PHP_FORMAT);

        $availableRules = $this->ruleResource->getSortedRuleDataForProduct(
            $productId,
            $customerGroupId,
            $storeId,
            $currentDate
        );

        return $availableRules;
    }

    /**
     * Prepare available promo items
     *
     * @param array $ruleProductData
     * @return PromoInterface[]
     */
    public function getPromoItems($ruleProductData)
    {
        $promoItems = [];
        foreach ($ruleProductData as $ruleData) {
            $promoItem = $this->promoFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $promoItem,
                $ruleData,
                PromoInterface::class
            );
            $promoItems[] = $promoItem;
        }

        return $promoItems;
    }
}

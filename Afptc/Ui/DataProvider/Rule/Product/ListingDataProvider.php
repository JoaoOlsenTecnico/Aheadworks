<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\Product;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Aheadworks\Afptc\Model\Source\Product\AllowedType;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Downloadable\Model\Product\Type as DownloadableProduct;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ListingDataProvider
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\Product
 */
class ListingDataProvider extends ProductDataProvider
{
    /**
     * @var ProductVisibility
     */
    private $productVisibility;

    /**
     * @var AllowedType
     */
    private $allowedProductType;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param ProductVisibility $productVisibility
     * @param AllowedType $allowedProductType
     * @param StoreManagerInterface $storeManager
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        ProductVisibility $productVisibility,
        AllowedType $allowedProductType,
        StoreManagerInterface $storeManager,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $collectionFactory,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );
        $this->productVisibility = $productVisibility;
        $this->allowedProductType = $allowedProductType;
        $this->storeManager = $storeManager;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $collection = $this->getCollection();
        $collection->setStoreId($this->storeManager->getStore()->getId());
        $this->applyLimitations($collection);
        return parent::getData();
    }

    /**
     * Apply limitations to product collection
     *
     * @param Collection|AbstractCollection $collection
     */
    public function applyLimitations($collection)
    {
        //todo it would be good to decompose this approach to make collection more flexible
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->addAttributeToSelect(ProductInterface::STATUS);
        $collection->addAttributeToFilter(
            ProductInterface::TYPE_ID,
            ['in' => $this->allowedProductType->getTypeList()]
        );
        $this->excludeProductsWithRequiredOptions($collection);
    }

    /**
     * Exclude products with required options
     *
     * @param Collection|AbstractCollection $collection
     */
    private function excludeProductsWithRequiredOptions($collection)
    {
        $collection->addFilterByRequiredOptions();

        //Downloadable products with required links
        $collection->addAttributeToSelect('links_purchased_separately', 'left');
        $condition1 = $collection->getConnection()->quoteInto('at_links_purchased_separately.value != ?', 1);
        $condition2 = $collection->getConnection()->quoteInto('e.type_id != ?', DownloadableProduct::TYPE_DOWNLOADABLE);
        $collection->getSelect()->where($condition1 . ' OR ' . $condition2);
    }
}

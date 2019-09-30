<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Condition\Cart;

use Magento\Rule\Model\Condition\Product\AbstractProduct;
use Magento\Framework\Model\AbstractModel;
use Magento\Catalog\Model\Product as ProductModel;

/**
 * Class Product
 *
 * @package Aheadworks\Afptc\Model\Rule\Condition\Cart
 */
class Product extends AbstractProduct
{
    /**
     * Add special attributes
     *
     * @param array $attributes
     * @return void
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
        $attributes['quote_item_qty'] = __('Quantity in cart');
        $attributes['quote_item_price'] = __('Price in cart');
        $attributes['quote_item_row_total'] = __('Row total in cart');
    }

    /**
     * Validate product rule condition
     *
     * @param AbstractModel $model
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function validate(AbstractModel $model)
    {
        if ($model instanceof ProductModel) {
            $product = $model;
        } else {
            /** @var ProductModel $product */
            $product = $model->getProduct();
            if (!$product instanceof ProductModel) {
                $product = $this->productRepository->getById($model->getProductId());
            }
        }

        $product->setQuoteItemQty(
            $model->getQty()
        )->setQuoteItemPrice(
            $model->getPrice()
        )->setQuoteItemRowTotal(
            $model->getBaseRowTotal()
        );

        $attrCode = $this->getAttribute();

        if ('category_ids' == $attrCode) {
            return $this->validateAttribute($this->_getAvailableInCategories($product->getId()));
        }

        return parent::validate($product);
    }

    /**
     * Retrieve value element chooser URL
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
            case 'sku':
            case 'category_ids':
                $url = 'sales_rule/promo_widget/chooser/attribute/' . $this->getAttribute();
                if ($this->getJsFormObject()) {
                    $url .= '/form/' . $this->getJsFormObject();
                }
                break;
            default:
                break;
        }
        return $url !== false ? $this->_backendData->getUrl($url) : '';
    }
}

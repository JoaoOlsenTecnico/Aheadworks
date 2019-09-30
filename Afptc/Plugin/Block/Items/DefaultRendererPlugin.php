<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Block\Items;

use Magento\Bundle\Model\Product\Type as BundleProduct;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer;

/**
 * Class DefaultRendererPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Block\Items
 */
class DefaultRendererPlugin
{
    /**
     * Add Afptc column after discount
     *
     * @param DefaultRenderer $subject
     * @param \Closure $proceed
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetColumns(
        DefaultRenderer $subject,
        \Closure $proceed
    ) {
        $columns = $proceed();
        foreach ($subject->getOrder()->getAllItems() as $orderItem) {
            if ($orderItem->getProductType() == BundleProduct::TYPE_CODE) {
                return $columns;
            }
        }
        $newColumns = [];
        foreach ($columns as $key => $column) {
            $newColumns[$key] = $column;
            if ($key == 'discont') {
                $newColumns['aw-afptc'] = 'col-aw-afptc';
            }
        }
        return $newColumns;
    }
}

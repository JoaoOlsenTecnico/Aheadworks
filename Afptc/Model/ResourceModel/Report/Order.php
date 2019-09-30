<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Report;

use Aheadworks\Afptc\Api\Data\OrderItemInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Aheadworks\Afptc\Api\Data\OrderInterface;
use Magento\Framework\DB\Select;
use Magento\Sales\Model\Order as SalesOrder;

/**
 * Class PromotionalCampaignsPerformance
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Report
 */
class Order extends OrderCollection
{
    /**
     * Retrieve order statistics
     *
     * @param bool $withPromo
     * @param bool $convertToGlobalRate
     * @return array
     */
    public function getStatistics($withPromo, $convertToGlobalRate)
    {
        $connection = $this->getConnection();
        $fieldToGlobalRate = $convertToGlobalRate ? ' * ' . OrderInterface::BASE_TO_GLOBAL_RATE : '';
        $select = $connection->select()
            ->from(
                new \Zend_Db_Expr('(' . $this->getOrderStatisticsQuery($withPromo) . ')'),
                [
                    'monthly_value' => new \Zend_Db_Expr(
                        'COALESCE(' . 'SUM(' . OrderInterface::BASE_TOTAL_INVOICED . $fieldToGlobalRate . ')' . ', 0)'
                    ),
                    'order_qty' => new \Zend_Db_Expr(
                        'COALESCE(COUNT(*), 0)'
                    ),
                    'item_qty' => new \Zend_Db_Expr(
                        'SUM(item_qty)'
                    ),
                ]
            );

        return $connection->fetchRow($select);
    }

    /**
     * Retrieve order statistics query
     *
     * @param bool $withPromo
     * @return Select
     */
    private function getOrderStatisticsQuery($withPromo)
    {
        $select = $this->getSelect();
        $qtyItemField = OrderItemInterface::QTY_INVOICED;
        if ($withPromo) {
            $qtyItemField = OrderItemInterface::AW_AFPTC_QTY_INVOICED;
            $select->where('soi.' . OrderItemInterface::AW_AFPTC_IS_PROMO . '= ?', 1);
        }

        $select->columns([
            'item_qty' => new \Zend_Db_Expr(
                'COALESCE(SUM(soi.' . $qtyItemField . '), 0)'
            )
        ])->joinLeft(
            ['soi' => $this->getTable('sales_order_item')],
            'main_table.entity_id = soi.order_id',
            []
        )->where('main_table.state in(?)', [SalesOrder::STATE_PROCESSING, SalesOrder::STATE_COMPLETE])
        ->group('main_table.entity_id');

        return $select;
    }

    /**
     * Add date filter
     *
     * @param int $days
     * @return $this
     */
    public function addDateFilter($days)
    {
        $this
            ->getSelect()
            ->where('DATE(main_table.' . OrderInterface::CREATED_AT . ') >= CURRENT_DATE - INTERVAL ? DAY', $days);

        return $this;
    }

    /**
     * Add date filter
     *
     * @param array $storeIds
     * @return $this
     */
    public function addStoreFilter($storeIds)
    {
        $this
            ->getSelect()
            ->where('main_table.' . OrderInterface::STORE_ID . ' IN (?)', $storeIds);

        return $this;
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Report;

use Aheadworks\Afptc\Model\ResourceModel\Report\OrderFactory as OrderReportFactory;
use Aheadworks\Afptc\Model\ResourceModel\Report\Order as OrderReport;
use Aheadworks\Afptc\Model\Report\Filter\Processor;

/**
 * Class Order
 *
 * @package Aheadworks\Afptc\Model\Report
 */
class Order implements ReportStatisticInterface
{
    /**
     * @var OrderReportFactory
     */
    private $orderReportFactory;

    /**
     * @var Processor
     */
    private $filterProcessor;

    /**
     * @param OrderReportFactory $orderReportFactory
     * @param Processor $filterProcessor
     */
    public function __construct(
        OrderReportFactory $orderReportFactory,
        Processor $filterProcessor
    ) {
        $this->orderReportFactory = $orderReportFactory;
        $this->filterProcessor = $filterProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics($searchCriteria)
    {
        $days = 30;
        $storeIds = $this->filterProcessor->getStoreIds($searchCriteria);
        /** @var OrderReport $orderReport */
        $orderReport = $this->orderReportFactory->create();
        $orderReport->addDateFilter($days);

        /** @var OrderReport $promoOrderReport */
        $promoOrderReport = $this->orderReportFactory->create();
        $promoOrderReport->addDateFilter($days);

        if (!empty($storeIds)) {
            $orderReport->addStoreFilter($storeIds);
            $promoOrderReport->addStoreFilter($storeIds);
        }

        $convertToGlobalRate = empty($storeIds);
        $orderStat = $orderReport->getStatistics(false, $convertToGlobalRate);
        $promoOrderStat = $promoOrderReport->getStatistics(true, $convertToGlobalRate);
        return $this->calculateStatistics($orderStat, $promoOrderStat);
    }

    /**
     * Calculate statistics
     *
     * @param array $orderStat
     * @param array $promoOrderStat
     * @return array
     */
    private function calculateStatistics($orderStat, $promoOrderStat)
    {
        $promoMonthlyValue = $promoOrderStat['monthly_value'];
        $monthlyValue = $orderStat['monthly_value'];
        $promoOrderQty = $promoOrderStat['order_qty'];
        $orderQty = $orderStat['order_qty'];
        $promoItemQty = $promoOrderStat['item_qty'];

        $promoMonthlyValueCompare = $monthlyValue > 0
            ? $promoMonthlyValue / $monthlyValue * 100
            : 0;
        $promoOrderQtyCompare = $orderQty > 0
            ? $promoOrderQty / $orderQty * 100
            : 0;
        $averageCartTotal = $promoOrderQty > 0
            ? $promoMonthlyValue / $promoOrderQty
            : 0;
        $averageCartTotalCompare = $orderQty > 0
            ? $monthlyValue / $orderQty
            : 0;
        $promoItemsPerOrder = $promoOrderQty > 0
            ? $promoItemQty / $promoOrderQty
            : 0;

        $statistics = [
            'monthly_value' => $promoMonthlyValue,
            'monthly_value_compare' => $promoMonthlyValueCompare,
            'order_qty' => $promoOrderQty,
            'order_qty_compare' => $promoOrderQtyCompare,
            'average_cart_total' => $averageCartTotal,
            'average_cart_total_compare' => $averageCartTotalCompare,
            'promo_items_per_order' => $promoItemsPerOrder
        ];
        return $statistics;
    }
}

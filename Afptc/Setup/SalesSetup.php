<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Magento\Sales\Setup\SalesSetup as MagentoSalesSetup;
use Magento\Framework\DB\Ddl\Table;
use Aheadworks\Afptc\Api\Data\CreditmemoInterface;
use Aheadworks\Afptc\Api\Data\CreditmemoItemInterface;
use Aheadworks\Afptc\Api\Data\InvoiceInterface;
use Aheadworks\Afptc\Api\Data\InvoiceItemInterface;
use Aheadworks\Afptc\Api\Data\OrderInterface;
use Aheadworks\Afptc\Api\Data\OrderItemInterface;

/**
 * Class SalesSetup
 *
 * @package Aheadworks\Afptc\Setup
 */
class SalesSetup extends MagentoSalesSetup
{
    /**
     * Retrieve attributes config to install
     *
     * @return array
     */
    public function getAttributesToInstall()
    {
        $attributes = [
            [
                'attribute' => CreditmemoInterface::AW_AFPTC_AMOUNT,
                'type' => 'creditmemo',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => CreditmemoInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'creditmemo',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],

            [
                'attribute' => CreditmemoItemInterface::AW_AFPTC_AMOUNT,
                'type' => 'creditmemo_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => CreditmemoItemInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'creditmemo_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],

            [
                'attribute' => InvoiceInterface::AW_AFPTC_AMOUNT,
                'type' => 'invoice',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => InvoiceInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'invoice',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],

            [
                'attribute' => InvoiceItemInterface::AW_AFPTC_AMOUNT,
                'type' => 'invoice_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => InvoiceItemInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'invoice_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],

            [
                'attribute' => OrderInterface::AW_AFPTC_AMOUNT,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::AW_AFPTC_INVOICED,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::BASE_AW_AFPTC_INVOICED,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::AW_AFPTC_REFUNDED,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::BASE_AW_AFPTC_REFUNDED,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderInterface::AW_AFPTC_USES_COUPON,
                'type' => 'order',
                'params' => ['type' => Table::TYPE_SMALLINT]
            ],

            [
                'attribute' => OrderItemInterface::AW_AFPTC_RULE_IDS,
                'type' => 'order_item',
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_PERCENT,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_QTY,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_QTY_INVOICED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_QTY_REFUNDED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_IS_PROMO,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_SMALLINT]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_AMOUNT,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::BASE_AW_AFPTC_AMOUNT,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_INVOICED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::BASE_AW_AFPTC_INVOICED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::AW_AFPTC_REFUNDED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ],
            [
                'attribute' => OrderItemInterface::BASE_AW_AFPTC_REFUNDED,
                'type' => 'order_item',
                'params' => ['type' => Table::TYPE_DECIMAL]
            ]
        ];

        return $attributes;
    }

    /**
     * Remove entity attribute. Overwritten for flat entities support
     *
     * @param int|string $entityTypeId
     * @param string $code
     * @return $this
     */
    public function removeAttribute($entityTypeId, $code)
    {
        if (isset($this->_flatEntityTables[$entityTypeId])) {
            if ($this->_flatTableExist($this->_flatEntityTables[$entityTypeId])) {
                $this->removeFlatAttribute($this->_flatEntityTables[$entityTypeId], $code);
            }
            if ($this->_flatTableExist($this->_flatEntityTables[$entityTypeId]) . '_grid') {
                $this->removeGridAttribute($this->_flatEntityTables[$entityTypeId] . '_grid', $code, $entityTypeId);
            }
        } else {
            parent::removeAttribute($entityTypeId, $code);
        }

        return $this;
    }

    /**
     * Remove attribute as separate column in the table
     *
     * @param string $table
     * @param string $attribute
     * @return $this
     */
    protected function removeFlatAttribute($table, $attribute)
    {
        $tableInfo = $this->getConnection()->describeTable($this->getTable($table));
        if (isset($tableInfo[$attribute])) {
            $this->getConnection()->dropColumn($this->getTable($table), $attribute);
        }

        return $this;
    }

    /**
     * Remove attribute from grid table if necessary
     *
     * @param string $table
     * @param string $attribute
     * @param string $entityTypeId
     * @return $this
     */
    protected function removeGridAttribute($table, $attribute, $entityTypeId)
    {
        $tableInfo = $this->getConnection()->describeTable($this->getTable($table));
        if (in_array($entityTypeId, $this->_flatEntitiesGrid) && isset($tableInfo[$attribute])) {
            $this->getConnection()->dropColumn($this->getTable($table), $attribute);
        }

        return $this;
    }
}

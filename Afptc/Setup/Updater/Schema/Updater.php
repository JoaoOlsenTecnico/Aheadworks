<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup\Updater\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Aheadworks\Afptc\Model\ResourceModel\Rule;

/**
 * Class Updater
 *
 * @package Aheadworks\Afptc\Setup\Updater\Schema
 */
class Updater
{
    /**
     * Update for 1.3.0 version
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    public function update130(SchemaSetupInterface $setup)
    {
        $this->addAdditionalLabelTextFieldsToRuleProductTables($setup);

        return $this;
    }

    /**
     * Add additional text fields to rule product table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addAdditionalLabelTextFieldsToRuleProductTables(SchemaSetupInterface $installer)
    {
        $tables = [
            Rule::PRODUCT_TABLE_NAME,
            Rule::PRODUCT_IDX_TABLE_NAME
        ];

        $connection = $installer->getConnection();
        foreach ($tables as $table) {
            if ($connection->tableColumnExists($installer->getTable($table), 'promo_on_sale_label_text')) {
                $connection->changeColumn(
                    $installer->getTable($table),
                    'promo_on_sale_label_text',
                    'promo_on_sale_label_text_large',
                    [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => 255,
                        'comment' => 'On Sale Large Label Text',
                    ]
                );
            }
            $this->addColumnsToTable(
                $installer,
                [
                    [
                        'fieldName' => 'promo_on_sale_label_text_medium',
                        'config' => [
                            'type' => Table::TYPE_TEXT,
                            'nullable' => true,
                            'length' => 255,
                            'after' => 'promo_on_sale_label_text_large',
                            'comment' => 'On Sale Medium Label Text'
                        ]
                    ],
                    [
                        'fieldName' => 'promo_on_sale_label_text_small',
                        'config' => [
                            'type' => Table::TYPE_TEXT,
                            'nullable' => true,
                            'length' => 255,
                            'after' => 'promo_on_sale_label_text_medium',
                            'comment' => 'On Sale Small Label Text'
                        ]
                    ]
                ],
                $table
            );
        }

        return $this;
    }

    /**
     * Add columns to table
     *
     * @param SchemaSetupInterface $setup
     * @param array $columnsConfig
     * @param string $tableName
     * @return $this
     */
    private function addColumnsToTable($setup, $columnsConfig, $tableName)
    {
        $connection = $setup->getConnection();
        $tableName = $setup->getTable($tableName);
        foreach ($columnsConfig as $fieldConfig) {
            $fieldName = $fieldConfig['fieldName'];
            if ($connection->tableColumnExists($tableName, $fieldName)) {
                continue;
            }
            $connection->addColumn(
                $tableName,
                $fieldName,
                $fieldConfig['config']
            );
        }

        return $this;
    }
}

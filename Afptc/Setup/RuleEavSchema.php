<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Aheadworks\Afptc\Model\ResourceModel\Rule;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Aheadworks\Afptc\Model\Source\Rule\Discount\Type;

/**
 * Class RuleEavSchema
 *
 * @package Aheadworks\Afptc\Setup
 */
class RuleEavSchema
{
    /**
     * Create rule tables
     *
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    public function createRuleTables(SchemaSetupInterface $setup)
    {
        $this
            ->createRuleTable($setup)
            ->createRuleVarcharTable($setup)
            ->createRuleIntTable($setup)
            ->createRuleTextTable($setup);
    }

    /**
     * Create table 'aw_afptc_rule'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable($setup->getTable(Rule::MAIN_TABLE_NAME))
            ->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true],
                'Rule ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                150,
                ['nullable' => false],
                'Name'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => true],
                'Description'
            )->addColumn(
                'active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Is Rule Active'
            )->addColumn(
                'from_date',
                Table::TYPE_DATE,
                null,
                [],
                'Rule is Active From'
            )->addColumn(
                'to_date',
                Table::TYPE_DATE,
                null,
                [],
                'Rule is Active To'
            )->addColumn(
                'priority',
                Table::TYPE_INTEGER,
                1,
                ['nullable' => false],
                'Priority'
            )->addColumn(
                'stop_rules_processing',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Stop Rules Processing'
            )->addColumn(
                'in_stock_offer_only',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is In Stock Offer Only'
            )->addColumn(
                'scenario',
                Table::TYPE_TEXT,
                70,
                ['nullable' => false],
                'Scenario'
            )->addColumn(
                'cart_conditions',
                Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'Cart Conditions'
            )->addColumn(
                'simple_action',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Simple Action'
            )->addColumn(
                'simple_action_n',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => true],
                'Simple Action N'
            )->addColumn(
                'qty_to_give',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Qty To Give'
            )->addColumn(
                'discount_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Discount Amount'
            )->addColumn(
                'discount_type',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false, 'default' => Type::PERCENT],
                'Discount Type'
            )->addColumn(
                'coupon_code',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Coupon Code'
            )->addColumn(
                'coupon_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Coupon ID'
            )->addColumn(
                'how_to_offer',
                Table::TYPE_TEXT,
                70,
                ['nullable' => false],
                'How to Offer Promo Products'
            )->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_NAME, ['active', 'priority', 'to_date', 'from_date']),
                ['active', 'priority', 'to_date', 'from_date']
            )->addForeignKey(
                $setup->getFkName(Rule::MAIN_TABLE_NAME, 'coupon_id', 'salesrule_coupon', 'coupon_id'),
                'coupon_id',
                $setup->getTable('salesrule_coupon'),
                'coupon_id',
                Table::ACTION_SET_NULL
            )->setComment('AW Afptc Rule Table');
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table 'aw_afptc_rule_entity_varchar'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleVarcharTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable($setup->getTable(Rule::MAIN_TABLE_VARCHAR_NAME))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )->addIndex(
                $setup->getIdxName(
                    Rule::MAIN_TABLE_VARCHAR_NAME,
                    ['rule_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_VARCHAR_NAME, ['attribute_id']),
                ['attribute_id']
            )->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_VARCHAR_NAME, ['store_id']),
                ['store_id']
            )->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_VARCHAR_NAME,
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_VARCHAR_NAME,
                    'rule_id',
                    Rule::MAIN_TABLE_NAME,
                    'rule_id'
                ),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::MAIN_TABLE_VARCHAR_NAME, 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment('AW Afptc Rule Varchar Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table 'aw_afptc_promo_entity_int'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleIntTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable($setup->getTable(Rule::MAIN_TABLE_INT_NAME))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )->addColumn(
                'value',
                Table::TYPE_INTEGER,
                null,
                [],
                'Value'
            )->addIndex(
                $setup->getIdxName(
                    Rule::MAIN_TABLE_INT_NAME,
                    ['rule_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_INT_NAME, ['attribute_id']),
                ['attribute_id']
            )->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_INT_NAME, ['store_id']),
                ['store_id']
            )->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_INT_NAME,
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_INT_NAME,
                    'rule_id',
                    Rule::MAIN_TABLE_NAME,
                    'rule_id'
                ),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::MAIN_TABLE_INT_NAME, 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment('AW Afptc Rule Int Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table 'aw_afptc_rule_entity_text'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleTextTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable($setup->getTable(Rule::MAIN_TABLE_TEXT_NAME))
            ->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                '64k',
                [],
                'Value'
            )->addIndex(
                $setup->getIdxName(
                    Rule::MAIN_TABLE_TEXT_NAME,
                    ['rule_id', 'attribute_id', 'store_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['rule_id', 'attribute_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_TEXT_NAME, ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $setup->getIdxName(Rule::MAIN_TABLE_TEXT_NAME, ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_TEXT_NAME,
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    Rule::MAIN_TABLE_TEXT_NAME,
                    'rule_id',
                    Rule::MAIN_TABLE_NAME,
                    'rule_id'
                ),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(Rule::MAIN_TABLE_TEXT_NAME, 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('AW Afptc Rule Text Attribute Backend Table');
        $setup->getConnection()->createTable($table);

        return $this;
    }
}

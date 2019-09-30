<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Aheadworks\Afptc\Model\ResourceModel\Rule;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Aheadworks\Afptc\Setup\Updater\Schema\Updater as SchemaUpdater;

/**
 * Class InstallSchema
 *
 * @package Aheadworks\Afptc\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var RuleEavSchema
     */
    private $ruleEavSchema;

    /**
     * @var SchemaUpdater
     */
    private $updater;

    /**
     * @param RuleEavSchema $ruleEavSchema
     * @param SchemaUpdater $updater
     */
    public function __construct(
        RuleEavSchema $ruleEavSchema,
        SchemaUpdater $updater
    ) {
        $this->ruleEavSchema = $ruleEavSchema;
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $this->ruleEavSchema->createRuleTables($installer);
        $this
            ->createRuleWebsiteTable($installer)
            ->createRuleCustomerGroupTable($installer)
            ->createRulePromoProductTable($installer)
            ->createRuleProductTable($installer, Rule::PRODUCT_TABLE_NAME)
            ->createRuleProductTable($installer, Rule::PRODUCT_IDX_TABLE_NAME)
            ->createRuleProductAttributeTable($installer);

        $this->updater->update130($setup);

        $installer->endSetup();
    }

    /**
     * Create table 'aw_afptc_rule_website'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleWebsiteTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable($setup->getTable(Rule::WEBSITE_TABLE_NAME))
            ->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Rule ID'
            )->addColumn(
                'website_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Website ID'
            )->addIndex(
                $setup->getIdxName(Rule::WEBSITE_TABLE_NAME, ['rule_id']),
                ['rule_id']
            )->addIndex(
                $setup->getIdxName(Rule::WEBSITE_TABLE_NAME, ['website_id']),
                ['website_id']
            )->addForeignKey(
                $setup->getFkName(Rule::WEBSITE_TABLE_NAME, 'rule_id', Rule::MAIN_TABLE_NAME, 'rule_id'),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::WEBSITE_TABLE_NAME, 'website_id', 'store_website', 'website_id'),
                'website_id',
                $setup->getTable('store_website'),
                'website_id',
                Table::ACTION_CASCADE
            )->setComment('AW Afptc Rule To Website Relation Table');
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table 'aw_afptc_rule_customer_group'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleCustomerGroupTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable(Rule::CUSTOMER_GROUP_TABLE_NAME)
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Rule ID'
            )->addColumn(
                'customer_group_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group ID'
            )->addIndex(
                $setup->getIdxName(Rule::CUSTOMER_GROUP_TABLE_NAME, ['rule_id']),
                ['rule_id']
            )->addIndex(
                $setup->getIdxName(Rule::CUSTOMER_GROUP_TABLE_NAME, ['customer_group_id']),
                ['customer_group_id']
            )->addForeignKey(
                $setup->getFkName(
                    Rule::CUSTOMER_GROUP_TABLE_NAME,
                    'customer_group_id',
                    'customer_group',
                    'customer_group_id'
                ),
                'customer_group_id',
                $setup->getTable('customer_group'),
                'customer_group_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::CUSTOMER_GROUP_TABLE_NAME, 'rule_id', Rule::MAIN_TABLE_NAME, 'rule_id'),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->setComment(
                'AW Afptc Rule To Customer Group Relation Table'
            );
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table 'aw_afptc_rule_promo_product'
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRulePromoProductTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable(Rule::PROMO_PRODUCT_TABLE_NAME)
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Rule ID'
            )->addColumn(
                'product_sku',
                Table::TYPE_TEXT,
                255,
                ['unsigned' => true, 'nullable' => false],
                'Product Sku'
            )->addColumn(
                'option',
                Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'Product Options'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Position'
            )->addIndex(
                $setup->getIdxName(Rule::PROMO_PRODUCT_TABLE_NAME, ['rule_id']),
                ['rule_id']
            )->addIndex(
                $setup->getIdxName(Rule::PROMO_PRODUCT_TABLE_NAME, ['product_sku']),
                ['product_sku']
            )->addIndex(
                $setup->getIdxName(Rule::PROMO_PRODUCT_TABLE_NAME, ['position']),
                ['position']
            )->addForeignKey(
                $setup->getFkName(Rule::PROMO_PRODUCT_TABLE_NAME, 'rule_id', Rule::MAIN_TABLE_NAME, 'rule_id'),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->setComment(
                'AW Afptc Rule To Promo Product Relation Table'
            );
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table for rule index
     *
     * @param SchemaSetupInterface $setup
     * @param string $tableName
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleProductTable(SchemaSetupInterface $setup, $tableName)
    {
        $table = $setup->getConnection()->newTable($setup->getTable($tableName))
            ->addColumn(
                'rule_product_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Rule Product Id'
            ) ->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Rule Id'
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
                'customer_group_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer Group Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Product Id'
            )->addColumn(
                'priority',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Priority'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store Id'
            )->addColumn(
                'promo_offer_info_text',
                Table::TYPE_TEXT,
                150,
                ['nullable' => true],
                'Promo Offer Info Text'
            )->addColumn(
                'promo_on_sale_label_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'On Sale Label Id'
            )->addColumn(
                'promo_on_sale_label_text',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'On Sale Label Text'
            )->addColumn(
                'promo_image',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Promo Image'
            )->addColumn(
                'promo_image_alt_text',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Promo Image Alt Text'
            )->addColumn(
                'promo_header',
                Table::TYPE_TEXT,
                150,
                ['nullable' => true],
                'Promo Header'
            )->addColumn(
                'promo_description',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => true],
                'Promo Description'
            )->addColumn(
                'promo_url',
                Table::TYPE_TEXT,
                '2k',
                ['nullable' => true],
                'Promo Url'
            )->addColumn(
                'promo_url_text',
                Table::TYPE_TEXT,
                150,
                ['nullable' => true],
                'Promo Url Text'
            )->addIndex(
                $setup->getIdxName(
                    $tableName,
                    ['rule_id', 'from_date', 'to_date', 'store_id', 'customer_group_id', 'product_id', 'priority'],
                    true
                ),
                ['rule_id', 'from_date', 'to_date', 'store_id', 'customer_group_id', 'product_id', 'priority'],
                ['type' => 'unique']
            )
            ->addIndex(
                $setup->getIdxName($tableName, ['customer_group_id']),
                ['customer_group_id']
            )
            ->addIndex(
                $setup->getIdxName($tableName, ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName($tableName, ['from_date']),
                ['from_date']
            )
            ->addIndex(
                $setup->getIdxName($tableName, ['to_date']),
                ['to_date']
            )
            ->addIndex(
                $setup->getIdxName($tableName, ['product_id']),
                ['product_id']
            )
            ->setComment('AW Afptc Rule Product');
        $setup->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Create table for product attributes index used in rule conditions
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createRuleProductAttributeTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME)
            )->addColumn(
                'rule_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Rule Id'
            )->addColumn(
                'website_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website Id'
            )->addColumn(
                'customer_group_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Customer Group Id'
            )->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attribute Id'
            )->addIndex(
                $setup->getIdxName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, ['website_id']),
                ['website_id']
            )->addIndex(
                $setup->getIdxName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, ['customer_group_id']),
                ['customer_group_id']
            )->addIndex(
                $setup->getIdxName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, ['attribute_id']),
                ['attribute_id']
            )->addForeignKey(
                $setup->getFkName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, 'attribute_id', 'eav_attribute', 'attribute_id'),
                'attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    Rule::PRODUCT_ATTRIBUTE_TABLE_NAME,
                    'customer_group_id',
                    'customer_group',
                    'customer_group_id'
                ),
                'customer_group_id',
                $setup->getTable('customer_group'),
                'customer_group_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, 'rule_id', Rule::MAIN_TABLE_TEXT_NAME, 'rule_id'),
                'rule_id',
                $setup->getTable(Rule::MAIN_TABLE_TEXT_NAME),
                'rule_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(Rule::PRODUCT_ATTRIBUTE_TABLE_NAME, 'website_id', 'store_website', 'website_id'),
                'website_id',
                $setup->getTable('store_website'),
                'website_id',
                Table::ACTION_CASCADE
            )->setComment(
                'AW Afptc Rule Product Attribute'
            );
        $setup->getConnection()->createTable($table);

        return $this;
    }
}

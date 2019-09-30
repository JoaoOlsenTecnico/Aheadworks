<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Aheadworks\Afptc\Model\ResourceModel\Rule;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aheadworks\Afptc\Model\Rule as ModelRule;

/**
 * Class Uninstall
 *
 * @package Aheadworks\Afptc\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $dataSetup;

    /**
     * @param QuoteSetupFactory $setupFactory
     * @param ModuleDataSetupInterface $dataSetup
     */
    public function __construct(
        QuoteSetupFactory $setupFactory,
        ModuleDataSetupInterface $dataSetup
    ) {
        $this->quoteSetupFactory = $setupFactory;
        $this->dataSetup = $dataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $this
            ->uninstallTables($installer)
            ->uninstallQuoteData()
            ->uninstallRuleEavData($installer)
            ->uninstallConfigData($installer)
            ->uninstallFlagData($installer);

        $installer->endSetup();
    }

    /**
     * Uninstall all module tables
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallTables(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->dropTable($installer->getTable(RULE::MAIN_TABLE_VARCHAR_NAME));
        $connection->dropTable($installer->getTable(RULE::MAIN_TABLE_INT_NAME));
        $connection->dropTable($installer->getTable(RULE::MAIN_TABLE_TEXT_NAME));
        $connection->dropTable($installer->getTable(RULE::WEBSITE_TABLE_NAME));
        $connection->dropTable($installer->getTable(RULE::CUSTOMER_GROUP_TABLE_NAME));
        $connection->dropTable($installer->getTable(RULE::PROMO_PRODUCT_TABLE_NAME));
        $connection->dropTable($installer->getTable(RULE::PRODUCT_ATTRIBUTE_TABLE_NAME));
        $connection->dropTable($installer->getTable(RULE::PRODUCT_TABLE_NAME));
        $connection->dropTable($installer->getTable(Rule::PRODUCT_IDX_TABLE_NAME));
        $connection->dropTable($installer->getTable(RULE::MAIN_TABLE_NAME));
        return $this;
    }

    /**
     * Uninstall quote data
     *
     * @return $this
     */
    private function uninstallQuoteData()
    {
        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $this->dataSetup]);
        $attributes = $quoteSetup->getAttributesToInstall();
        foreach ($attributes as $attributeCode => $attributeParams) {
            $quoteSetup->removeAttribute(
                $attributeParams['type'],
                $attributeParams['attribute']
            );
        }
        return $this;
    }

    /**
     * Uninstall module rule eav data with all attributes
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallRuleEavData(SchemaSetupInterface $installer)
    {
        $select = $installer->getConnection()->select()
            ->from($installer->getTable('eav_entity_type'), 'entity_type_id')
            ->where('entity_type_code = ?', ModelRule::ENTITY);
        $entityTypeId = $installer->getConnection()->fetchCol($select);

        $condition = ['entity_type_id = ?' => $entityTypeId];
        $installer->getConnection()->delete($installer->getTable('eav_entity_type'), $condition);
        $installer->getConnection()->delete($installer->getTable('eav_attribute'), $condition);
        $installer->getConnection()->delete($installer->getTable('eav_entity_attribute'), $condition);
        $installer->getConnection()->delete($installer->getTable('eav_attribute_set'), $condition);
        return $this;
    }

    /**
     * Uninstall module data from config
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallConfigData(SchemaSetupInterface $installer)
    {
        $configTable = $installer->getTable('core_config_data');
        $installer->getConnection()->delete($configTable, "`path` LIKE 'aw_afptc%'");
        return $this;
    }

    /**
     * Uninstall module data from flag table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallFlagData(SchemaSetupInterface $installer)
    {
        $flagTable = $installer->getTable('flag');
        $installer->getConnection()->delete($flagTable, "`flag_code` LIKE 'aw_afptc%'");

        return $this;
    }
}

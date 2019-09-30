<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aheadworks\Afptc\Model\Rule as RuleModel;

/**
 * Class InstallData
 *
 * @package Aheadworks\Afptc\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * @var RuleSetupFactory
     */
    private $ruleSetupFactory;

    /**
     * @param QuoteSetupFactory $setupFactory
     * @param SalesSetupFactory $salesSetupFactory
     * @param RuleSetupFactory $ruleSetupFactory
     */
    public function __construct(
        QuoteSetupFactory $setupFactory,
        SalesSetupFactory $salesSetupFactory,
        RuleSetupFactory $ruleSetupFactory
    ) {
        $this->quoteSetupFactory = $setupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->ruleSetupFactory = $ruleSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this
            ->installSalesAttributes($setup)
            ->installQuoteAttributes($setup)
            ->installRuleAttributes($setup);
    }

    /**
     * Install sales attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function installSalesAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $attributes = $salesSetup->getAttributesToInstall();
        foreach ($attributes as $attributeParams) {
            $salesSetup->addAttribute(
                $attributeParams['type'],
                $attributeParams['attribute'],
                $attributeParams['params']
            );
        }

        return $this;
    }

    /**
     * Install quote attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function installQuoteAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $attributes = $quoteSetup->getAttributesToInstall();
        foreach ($attributes as $attributeCode => $attributeParams) {
            $quoteSetup->addAttribute(
                $attributeParams['type'],
                $attributeParams['attribute'],
                $attributeParams['params']
            );
        }

        return $this;
    }

    /**
     * Install rule attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function installRuleAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var RuleSetup $ruleSetup */
        $ruleSetup = $this->ruleSetupFactory->create(['setup' => $setup]);
        $ruleSetup->installEntities();
        $attributes = $ruleSetup->getAttributesToInstall();
        foreach ($attributes as $attributeCode => $attributeParams) {
            $ruleSetup->addAttribute(
                RuleModel::ENTITY,
                $attributeParams['attribute'],
                $attributeParams['params']
            );
        }

        return $this;
    }
}

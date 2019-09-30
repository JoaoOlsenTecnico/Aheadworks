<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup\Updater\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aheadworks\Afptc\Setup\RuleSetup;
use Aheadworks\Afptc\Setup\RuleSetupFactory;
use Aheadworks\Afptc\Model\Rule as RuleModel;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Updater
 *
 * @package Aheadworks\Afptc\Setup\Updater\Data
 */
class Updater
{
    /**
     * @var RuleSetupFactory
     */
    private $ruleSetupFactory;

    /**
     * @param RuleSetupFactory $ruleSetupFactory
     */
    public function __construct(
        RuleSetupFactory $ruleSetupFactory
    ) {
        $this->ruleSetupFactory = $ruleSetupFactory;
    }

    /**
     * Update for 1.3.0 version
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function update130(ModuleDataSetupInterface $setup)
    {
        $this->addOnSaleLabelTextAttributes($setup);
        return $this;
    }

    /**
     * Update for 1.4.0 version
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function update140(ModuleDataSetupInterface $setup)
    {
        $this->updateOnSaleLabelTextAttributes($setup);
        return $this;
    }

    /**
     * Add On Sale label text attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function addOnSaleLabelTextAttributes($setup)
    {
        /** @var RuleSetup $ruleSetup */
        $ruleSetup = $this->ruleSetupFactory->create(['setup' => $setup]);
        $ruleSetup->updateAttribute(
            RuleModel::ENTITY,
            'promo_on_sale_label_text',
            'attribute_code',
            RuleInterface::PROMO_ON_SALE_LABEL_TEXT_LARGE
        );
        $ruleSetup->addAttribute(
            RuleModel::ENTITY,
            RuleInterface::PROMO_ON_SALE_LABEL_TEXT_MEDIUM,
            ['type' => 'varchar']
        );
        $ruleSetup->addAttribute(
            RuleModel::ENTITY,
            RuleInterface::PROMO_ON_SALE_LABEL_TEXT_SMALL,
            ['type' => 'varchar']
        );

        return $this;
    }

    /**
     * Update On Sale label text attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function updateOnSaleLabelTextAttributes($setup)
    {
        /** @var RuleSetup $ruleSetup */
        $ruleSetup = $this->ruleSetupFactory->create(['setup' => $setup]);
        if (!empty($ruleSetup->getAttribute(RuleModel::ENTITY, 'promo_on_sale_label_text_large'))) {
            $ruleSetup->updateAttribute(
                RuleModel::ENTITY,
                'promo_on_sale_label_text_large',
                'attribute_code',
                RuleInterface::PROMO_ON_SALE_LABEL_TEXT_LARGE
            );
        }
        if (!empty($ruleSetup->getAttribute(RuleModel::ENTITY, 'promo_on_sale_label_text_medium'))) {
            $ruleSetup->updateAttribute(
                RuleModel::ENTITY,
                'promo_on_sale_label_text_medium',
                'attribute_code',
                RuleInterface::PROMO_ON_SALE_LABEL_TEXT_MEDIUM
            );
        }
        if (!empty($ruleSetup->getAttribute(RuleModel::ENTITY, 'promo_on_sale_label_text_small'))) {
            $ruleSetup->updateAttribute(
                RuleModel::ENTITY,
                'promo_on_sale_label_text_small',
                'attribute_code',
                RuleInterface::PROMO_ON_SALE_LABEL_TEXT_SMALL
            );
        }

        return $this;
    }
}

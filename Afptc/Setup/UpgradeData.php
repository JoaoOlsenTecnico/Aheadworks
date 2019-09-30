<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aheadworks\Afptc\Setup\Updater\Data\Updater as DataUpdater;

/**
 * Class UpgradeData
 *
 * @package Aheadworks\Afptc\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var DataUpdater
     */
    private $updater;

    /**
     * @param DataUpdater $updater
     */
    public function __construct(
        DataUpdater $updater
    ) {
        $this->updater = $updater;
    }

    /**
     * @inheritdoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.3.0', '<')) {
            $this->updater->update130($setup);
        }
        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $this->updater->update140($setup);
        }

        $setup->endSetup();
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ThirdPartyModule;

use Magento\Framework\Module\ModuleListInterface;

/**
 * Class Manager
 *
 * @package Aheadworks\Afptc\Model\ThirdPartyModule
 */
class Manager
{
    /**
     * Aheadworks On Sale module name
     */
    const ON_SALE_MODULE_NAME = 'Aheadworks_OnSale';

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
    }

    /**
     * Check if Aheadworks On Sale module enabled
     *
     * @return bool
     */
    public function isOnSaleEnabled()
    {
        return $this->moduleList->has(self::ON_SALE_MODULE_NAME);
    }
}

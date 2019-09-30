<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Aheadworks\Afptc\Model\ThirdPartyModule\Manager;

/**
 * Class ThirdPartyModule
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class ThirdPartyModule implements ProcessorInterface
{
    /**
     * Key for keeping value of on sale module status
     */
    const ON_SALE_ENABLED_PARAM = 'onSaleEnabled';

    /**
     * @var Manager
     */
    private $thirdPartyModuleManager;

    /**
     * @param Manager $thirdPartyModuleManager
     */
    public function __construct(
        Manager $thirdPartyModuleManager
    ) {
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        $data[self::ON_SALE_ENABLED_PARAM] = $this->thirdPartyModuleManager->isOnSaleEnabled();
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        return $meta;
    }
}

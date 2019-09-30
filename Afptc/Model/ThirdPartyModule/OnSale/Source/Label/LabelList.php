<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ThirdPartyModule\OnSale\Source\Label;

use Aheadworks\Afptc\Model\ThirdPartyModule\Manager;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class LabelList
 *
 * @package Aheadworks\Afptc\Model\ThirdPartyModule\OnSale\Source\Label
 */
class LabelList implements OptionSourceInterface
{
    /**
     * Don't use variable
     */
    const DO_NOT_USE = 'do_not_use';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Manager
     */
    private $thirdPartyModuleManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Manager $thirdPartyModuleManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Manager $thirdPartyModuleManager
    ) {
        $this->objectManager = $objectManager;
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = [['value' => self::DO_NOT_USE, 'label' => __('Don\'t Use')]];
        if ($this->thirdPartyModuleManager->isOnSaleEnabled()) {
            $options = array_merge($options, $this->getLabelOptions()->toOptionArray());
        }

        return $options;
    }

    /**
     * Retrieve label options object
     *
     * @return \Aheadworks\OnSale\Model\Source\Label\Options
     */
    private function getLabelOptions()
    {
        return $this->objectManager->create(\Aheadworks\OnSale\Model\Source\Label\Options::class);
    }
}

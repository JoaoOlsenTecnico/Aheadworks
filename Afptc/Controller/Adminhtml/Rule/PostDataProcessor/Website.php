<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Website
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class Website implements ProcessorInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare website for save
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function process($data)
    {
        if ($this->storeManager->hasSingleStore()) {
            $defaultWebsite = $this->storeManager->getWebsite();
            $data[RuleInterface::WEBSITE_IDS] = [$defaultWebsite->getId()];
        }
        return $data;
    }
}

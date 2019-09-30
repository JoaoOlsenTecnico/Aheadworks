<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\Component\Listing\Column\Rule;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Website
 *
 * @package Aheadworks\Afptc\Ui\Component\Listing\Column\Rule
 */
class Website extends Column
{
    /**
     * @var WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param WebsiteRepositoryInterface $websiteRepository
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        WebsiteRepositoryInterface $websiteRepository,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            $websiteIds = $item[RuleInterface::WEBSITE_IDS];
            if (!is_array($websiteIds)) {
                continue;
            }
            $websiteNames = [];
            foreach ($websiteIds as $websiteId) {
                try {
                    $websiteNames[] = $this->websiteRepository->getById($websiteId)->getName();
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
            $item[$fieldName] = implode(', ', $websiteNames);
        }
        return $dataSource;
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if ($this->storeManager->hasSingleStore()) {
            $config['componentDisabled'] = true;
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}

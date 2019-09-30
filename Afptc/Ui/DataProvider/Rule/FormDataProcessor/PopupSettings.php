<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\Store;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Attribute\ScopeOverriddenValue;
use Magento\Framework\Registry;
use Aheadworks\Afptc\Controller\Adminhtml\Rule\Edit;

/**
 * Class PopupSettings
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class PopupSettings implements ProcessorInterface
{
    /**
     * Config path
     */
    const CONFIG_PATH_PART = 'popup_settings';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var ScopeOverriddenValue
     */
    private $scopeOverriddenValue;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param RequestInterface $request
     * @param ArrayManager $arrayManager
     * @param ScopeOverriddenValue $scopeOverriddenValue
     * @param Registry $coreRegistry
     */
    public function __construct(
        RequestInterface $request,
        ArrayManager $arrayManager,
        ScopeOverriddenValue $scopeOverriddenValue,
        Registry $coreRegistry
    ) {
        $this->request = $request;
        $this->arrayManager = $arrayManager;
        $this->scopeOverriddenValue = $scopeOverriddenValue;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        if (!array_key_exists(RuleInterface::POPUP_HEADER_TEXT, $data)
            && !isset($data[RuleInterface::POPUP_HEADER_TEXT])
        ) {
            $data[RuleInterface::POPUP_HEADER_TEXT] = __('Your special offer');
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        $storeId = $this->request->getParam('store', Store::DEFAULT_STORE_ID);
        $fieldPath = self::CONFIG_PATH_PART . '/children/' . RuleInterface::POPUP_HEADER_TEXT;
        $rule = $this->coreRegistry = $this->coreRegistry->registry(Edit::KEY_RULE_DATA);

        if ($storeId != Store::DEFAULT_STORE_ID) {
            $meta = $this->arrayManager->set(
                $fieldPath . '/arguments/data/config',
                $meta,
                [
                    'service' => [
                        'template' => 'ui/form/element/helper/service',
                    ],
                    'disabled' => !$this->scopeOverriddenValue->containsValue(
                        RuleInterface::class,
                        $rule,
                        RuleInterface::POPUP_HEADER_TEXT,
                        $storeId
                    )
                ]
            );
        }

        return $meta;
    }
}

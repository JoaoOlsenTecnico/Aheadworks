<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Eav\Model\Config as EavConfig;
use Aheadworks\Afptc\Model\Rule;
use Magento\Store\Model\Store;
use Magento\Eav\Model\Entity\Attribute;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Attribute\ScopeOverriddenValue;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Registry;
use Aheadworks\Afptc\Controller\Adminhtml\Rule\Edit;

/**
 * Class PromoSettings
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class PromoSettings implements ProcessorInterface
{
    /**
     * Config path
     */
    const CONFIG_PATH_PART = 'promo_settings';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var EavConfig
     */
    private $eavConfig;

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
     * @param EavConfig $eavConfig
     * @param ScopeOverriddenValue $scopeOverriddenValue
     * @param Registry $coreRegistry
     */
    public function __construct(
        RequestInterface $request,
        ArrayManager $arrayManager,
        EavConfig $eavConfig,
        ScopeOverriddenValue $scopeOverriddenValue,
        Registry $coreRegistry
    ) {
        $this->request = $request;
        $this->eavConfig = $eavConfig;
        $this->arrayManager = $arrayManager;
        $this->scopeOverriddenValue = $scopeOverriddenValue;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        $promoAttributes = $this->preparePromoAttributes();
        $storeId = $this->request->getParam('store', Store::DEFAULT_STORE_ID);
        $rule = $this->coreRegistry = $this->coreRegistry->registry(Edit::KEY_RULE_DATA);

        foreach ($promoAttributes as $promoAttribute) {
            $fieldPath = self::CONFIG_PATH_PART . '/children/' . $promoAttribute->getAttributeCode();

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
                            $promoAttribute->getAttributeCode(),
                            $storeId
                        )
                    ]
                );
            }
        }

        return $meta;
    }

    /**
     * Prepare promo attributes
     *
     * @return Attribute[]
     */
    private function preparePromoAttributes()
    {
        $promoAttributes = [];
        $eavAttributes = $this->eavConfig->getEntityAttributes(Rule::ENTITY);
        foreach ($eavAttributes as $eavAttribute) {
            if ($eavAttribute->isStatic()) {
                continue;
            }

            if (strpos($eavAttribute->getAttributeCode(), 'promo_') !== false) {
                $promoAttributes[] = $eavAttribute;
            }
        }

        return $promoAttributes;
    }
}

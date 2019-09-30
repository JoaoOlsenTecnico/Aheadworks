<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule;

use Aheadworks\Afptc\Model\ResourceModel\Rule\CollectionFactory;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor\Composite;
use Magento\Store\Model\Store;

/**
 * Class FormDataProvider
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule
 */
class FormDataProvider extends AbstractDataProvider
{
    /**
     * Key for saving and getting form data from data persistor
     */
    const DATA_PERSISTOR_FORM_DATA_KEY = 'aw_afptc_rule';

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Composite
     */
    private $processor;

    /**
     * @var FormConfig
     */
    private $formConfig;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param Composite $processor
     * @param FormConfig $formConfig
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        Composite $processor,
        FormConfig $formConfig,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
        $this->processor = $processor;
        $this->formConfig = $formConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get(self::DATA_PERSISTOR_FORM_DATA_KEY);
        $storeId = $this->request->getParam('store', Store::DEFAULT_STORE_ID);

        if (!empty($dataFromForm) && (is_array($dataFromForm))) {
            $id = $dataFromForm[RuleInterface::RULE_ID];
            $this->dataPersistor->clear(self::DATA_PERSISTOR_FORM_DATA_KEY);
            $data = $dataFromForm;
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            $rules = $this->getCollection()
                ->setStoreId($storeId)
                ->addAttributeToSelect('*')
                ->addFieldToFilter(RuleInterface::RULE_ID, $id)
                ->getItems();
            /** @var RuleInterface $rule */
            foreach ($rules as $rule) {
                if ($id == $rule->getRuleId()) {
                    $data = $rule->getData();
                }
            }
        }
        $preparedData[$id] = $this->processor->prepareEntityData($data);
        $this->data['config'] = $this->formConfig->prepareConfig($storeId);

        return $preparedData;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        $meta = parent::getMeta();
        $meta = $this->processor->prepareMetaData($meta);

        return $meta;
    }
}

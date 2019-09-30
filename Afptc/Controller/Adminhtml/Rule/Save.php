<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Api\Data\RuleInterfaceFactory;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProvider as RuleFormDataProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Backend\App\Action as BackendAction;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\Store;
use Aheadworks\Afptc\Api\IndexManagementInterface;

/**
 * Class Save
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class Save extends BackendAction
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var RuleInterfaceFactory
     */
    private $ruleDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var PostDataProcessor
     */
    private $postDataProcessor;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var IndexManagementInterface
     */
    private $indexManagement;

    /**
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     * @param RuleInterfaceFactory $ruleDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param PostDataProcessor $postDataProcessor
     * @param DataObjectProcessor $dataObjectProcessor
     * @param IndexManagementInterface $indexManagement
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository,
        RuleInterfaceFactory $ruleDataFactory,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        PostDataProcessor $postDataProcessor,
        DataObjectProcessor $dataObjectProcessor,
        IndexManagementInterface $indexManagement
    ) {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
        $this->ruleDataFactory = $ruleDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->postDataProcessor = $postDataProcessor;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->indexManagement = $indexManagement;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $storeId = $this->getRequest()->getParam('store', Store::DEFAULT_STORE_ID);
            $data[RuleInterface::STORE_ID] = $storeId;

            try {
                $data = $this->postDataProcessor->prepareEntityData($data);
                $rule = $this->performSave($data);

                $this->dataPersistor->clear(RuleFormDataProvider::DATA_PERSISTOR_FORM_DATA_KEY);
                $this->messageManager->addSuccessMessage(__('Rule saved successfully'));

                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $rule->getRuleId(), 'store' => $storeId]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rule'));
            }
            $this->dataPersistor->set(RuleFormDataProvider::DATA_PERSISTOR_FORM_DATA_KEY, $data);
            $ruleId = isset($data['rule_id']) ? $data['rule_id'] : false;
            if ($ruleId) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $ruleId, 'store' => $storeId, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Perform save
     *
     * @param array $data
     * @return RuleInterface
     * @throws LocalizedException|\Exception
     */
    private function performSave($data)
    {
        $newRuleDataObject = $this->ruleDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $newRuleDataObject,
            $data,
            RuleInterface::class
        );

        $ruleId = isset($data['rule_id']) ? $data['rule_id'] : false;
        $ruleDataObject = $ruleId
            ? $this->ruleRepository->get($ruleId, $data['store_id'])
            : $this->ruleDataFactory->create();
        $oldRuleDataObject = clone $ruleDataObject;

        $this->dataObjectHelper->populateWithArray(
            $ruleDataObject,
            $data,
            RuleInterface::class
        );
        $convertedData = $this->dataObjectProcessor->buildOutputDataArray($ruleDataObject, RuleInterface::class);
        $this->dataObjectHelper->populateWithArray(
            $ruleDataObject,
            $convertedData,
            RuleInterface::class
        );

        $rule = $this->ruleRepository->save($ruleDataObject);
        $this->indexManagement->invalidateIndexOnDataChange($newRuleDataObject, $oldRuleDataObject);

        return $rule;
    }
}

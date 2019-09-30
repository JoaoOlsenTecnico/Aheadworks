<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;

/**
 * Class Delete
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class Delete extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RuleRepositoryInterface $ruleRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * Delete rule action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ruleId = (int)$this->getRequest()->getParam('id');
        if ($ruleId) {
            try {
                $this->ruleRepository->deleteById($ruleId);
                $this->messageManager->addSuccessMessage(__('Rule deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Something went wrong while deleting the rule.'));
        return $resultRedirect->setPath('*/*/');
    }
}

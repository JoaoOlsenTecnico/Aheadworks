<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;

/**
 * Class Edit
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class Edit extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * Key used for registry to store rule data
     */
    const KEY_RULE_DATA = 'rule_data';

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository,
        PageFactory $resultPageFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $ruleId = (int) $this->getRequest()->getParam('id');
        $storeId = (int) $this->getRequest()->getParam('store', Store::DEFAULT_STORE_ID);
        if ($ruleId) {
            try {
                $rule = $this->ruleRepository->get($ruleId, $storeId);
                $this->coreRegistry->register(self::KEY_RULE_DATA, $rule);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('This rule no longer exists.')
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Aheadworks_Afptc::rules')
            ->getConfig()->getTitle()->prepend(
                $ruleId
                    ? __('Edit "%1" rule', $rule->getName())
                    : __('New Rule')
            );
        return $resultPage;
    }
}

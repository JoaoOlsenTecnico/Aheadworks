<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Order;

use Aheadworks\Afptc\Controller\Product\AddToCart\PostDataProcessor;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Backend\Model\Session\Quote as BackendQuoteSession;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class AddPromoItems
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Order
 */
class AddPromoItems extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var BackendQuoteSession
     */
    private $backendQuoteSession;

    /**
     * @var PostDataProcessor
     */
    private $postDataProcessor;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param CartManagementInterface $cartManagement
     * @param BackendQuoteSession $backendQuoteSession
     * @param RuleManagementInterface $ruleManagement
     * @param PostDataProcessor $postDataProcessor
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CartManagementInterface $cartManagement,
        BackendQuoteSession $backendQuoteSession,
        RuleManagementInterface $ruleManagement,
        PostDataProcessor $postDataProcessor
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cartManagement = $cartManagement;
        $this->backendQuoteSession = $backendQuoteSession;
        $this->ruleManagement = $ruleManagement;
        $this->postDataProcessor = $postDataProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $requestItems = $this->getRequest()->getParam('items', []);

        $quote = $this->backendQuoteSession->getQuote();
        $quote->getAllVisibleItems();
        $metadataRules = $this->ruleManagement->getPopUpMetadataRules($quote->getId());

        $preparedItems = $this->postDataProcessor->prepareRequestItems($requestItems);
        $preparedMetadataRules = $this->postDataProcessor->prepareMetadataRulesByItems($metadataRules, $preparedItems);

        try {
            $this->cartManagement->addPromoProducts($quote->getId(), $preparedMetadataRules);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t add this promo product(s) right now.')
            );
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([]);
    }
}

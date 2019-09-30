<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Product;

use Aheadworks\Afptc\Controller\Product\AddToCart\PostDataProcessor;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Afptc\Api\CartManagementInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class AddToCart
 *
 * @package Aheadworks\Afptc\Controller\Product
 */
class AddToCart extends Action
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
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var PostDataProcessor
     */
    private $postDataProcessor;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param CartManagementInterface $cartManagement
     * @param CheckoutSession $checkoutSession
     * @param RuleManagementInterface $ruleManagement
     * @param PostDataProcessor $postDataProcessor
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CartManagementInterface $cartManagement,
        CheckoutSession $checkoutSession,
        RuleManagementInterface $ruleManagement,
        PostDataProcessor $postDataProcessor
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cartManagement = $cartManagement;
        $this->checkoutSession = $checkoutSession;
        $this->ruleManagement = $ruleManagement;
        $this->postDataProcessor = $postDataProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $requestItems = $this->getRequest()->getParam('items', []);

        $quote = $this->checkoutSession->getQuote();
        $metadataRules = $this->ruleManagement->getPopUpMetadataRules($quote->getId());

        $preparedItems = $this->postDataProcessor->prepareRequestItems($requestItems);
        $preparedMetadataRules = $this->postDataProcessor->prepareMetadataRulesByItems($metadataRules, $preparedItems);

        try {
            $this->cartManagement->addPromoProducts($quote->getId(), $preparedMetadataRules);
            $this->messageManager->addSuccessMessage(__('You added promo product(s) to your shopping cart.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t add this promo product(s) to your shopping cart right now.')
            );
        }

        if ($this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData([]);
            $result = $resultJson;
        } else {
            $redirectResult = $this->resultRedirectFactory->create();
            $redirectResult->setRefererUrl();
            $result = $redirectResult;
        }

        return $result;
    }
}

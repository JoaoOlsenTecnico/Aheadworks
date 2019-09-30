<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Afptc\Api\PromoOfferRenderListInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Framework\EntityManager\Hydrator;
use Magento\Backend\Model\Session\Quote as BackendQuoteSession;

/**
 * Class GetPromoOffer
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Order
 */
class GetPromoOffer extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Magento_Sales::create';

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var BackendQuoteSession
     */
    private $backendQuoteSession;

    /**
     * @var PromoOfferRenderListInterface
     */
    private $promoOfferRenderList;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param RuleManagementInterface $ruleManagement
     * @param BackendQuoteSession $backendQuoteSession
     * @param PromoOfferRenderListInterface $promoOfferRenderList
     * @param Hydrator $hydrator
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        RuleManagementInterface $ruleManagement,
        BackendQuoteSession $backendQuoteSession,
        PromoOfferRenderListInterface $promoOfferRenderList,
        Hydrator $hydrator
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->ruleManagement = $ruleManagement;
        $this->backendQuoteSession = $backendQuoteSession;
        $this->promoOfferRenderList = $promoOfferRenderList;
        $this->hydrator = $hydrator;
    }

    /**
     * Get promo offer data
     *
     * @return \Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            $quote = $this->backendQuoteSession->getQuote();
            $quote->getAllVisibleItems();
            $metadataRules = $this->ruleManagement->getPopUpMetadataRules($quote->getId());
            $promoOffer = $this->promoOfferRenderList->getList($metadataRules, $quote->getStore()->getStoreId());
            $result = [
                'error' => false,
                'promoOffer' => $this->hydrator->extract($promoOffer)
            ];

            return $resultJson->setData($result);
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}

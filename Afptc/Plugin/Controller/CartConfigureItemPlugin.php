<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Controller;

use Magento\Checkout\Controller\Cart\Configure;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class CartConfigureItemPlugin
 *
 * @package Aheadworks\Afptc\Plugin\Controller
 */
class CartConfigureItemPlugin
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param CheckoutSession $checkoutSession
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Check added coupon or deleted and add automatic products to the cart if needed
     *
     * @param Configure $subject
     * @param \Closure $proceed
     * @return Redirect
     */
    public function aroundExecute($subject, \Closure $proceed)
    {
        $id = (int)$subject->getRequest()->getParam('id');
        $quoteItem = null;
        if ($id) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($id);
        }
        if ($quoteItem && $quoteItem->getAwAfptcIsPromo()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setRefererUrl();
            $this->messageManager->addErrorMessage(__('Can not reconfigure promo product this way. ' .
                'Please delete the product from the cart, click a gift icon and choose a new one.'));

            return $resultRedirect;
        }
        return $proceed();
    }
}

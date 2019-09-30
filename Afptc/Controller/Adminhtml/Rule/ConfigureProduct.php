<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Afptc\Ui\DataProvider\Rule\Product\Info as ProductInfo;

/**
 * Class ConfigureProduct
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class ConfigureProduct extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var ProductInfo
     */
    private $productInfo;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param ProductInfo $productInfo
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ProductInfo $productInfo
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productInfo = $productInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();

            $productId = (int)$this->getRequest()->getParam('id');

            try {
                $productData = $this->productInfo->getData($productId);
                $result = [
                    'error' => false,
                    'product' => $productData

                ];
            } catch (\Exception $e) {
                $result = [
                    'error' => true,
                    'message' => __($e->getMessage())
                ];
            }

            return $resultJson->setData($result);
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}

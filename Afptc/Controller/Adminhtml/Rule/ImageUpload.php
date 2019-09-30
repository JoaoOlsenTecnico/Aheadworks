<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Aheadworks\Afptc\Model\Rule\Image\Uploader;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class ImageUpload
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class ImageUpload extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * @var string
     */
    const FILE_ID = 'image';

    /**
     * @var Uploader
     */
    private $imageUploader;

    /**
     * @param Context $context
     * @param Uploader $imageUploader
     */
    public function __construct(
        Context $context,
        Uploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Image upload action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        try {
            $result = $this->imageUploader->uploadToMediaFolder(self::FILE_ID);
        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }

        return $resultJson->setData($result);
    }
}

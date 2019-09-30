<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\Component\Form\Rule\Element\Button;

use Magento\Ui\Component\Form\Field;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Framework\App\RequestInterface;

/**
 * Class Image
 *
 * @package Aheadworks\Afptc\Ui\Component\Form\Rule\Element\Button
 */
class Image extends Field
{
    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param AssetRepository $assetRepository
     * @param RequestInterface $request
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        AssetRepository $assetRepository,
        RequestInterface $request,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->request = $request;
        $this->assetRepository = $assetRepository;
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if ($imagePath = $config['imagePath']) {
            $params = [
                '_secure' => $this->request->isSecure()
            ];
            $config['absoluteImagePath'] = $this->assetRepository->getUrlWithParams($imagePath, $params);
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}

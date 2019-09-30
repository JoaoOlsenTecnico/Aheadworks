<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Promo;

use Aheadworks\Afptc\Api\PromoInfoBlockRepositoryInterface;
use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Model\Rule\Promo\Renderer\Product\Resolver as ProductResolver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Aheadworks\Afptc\Block\Promo\Renderer\Popup;
use Aheadworks\Afptc\Model\Theme\View\Config as ViewConfig;

/**
 * Class Renderer
 *
 * @method Product getProduct()
 * @method string getPlacement()
 * @package Aheadworks\Afptc\Block\Promo
 */
class Renderer extends Template
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'Aheadworks_Afptc::promo/renderer.phtml';

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var ProductResolver
     */
    private $productResolver;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var ViewConfig
     */
    private $viewConfig;

    /**
     * @var array
     */
    private $promoInfoBlocks = [];

    /**
     * @param Context $context
     * @param PromoInfoBlockRepositoryInterface $blockRepository
     * @param ProductResolver $productResolver
     * @param HttpContext $httpContext
     * @param ViewConfig $viewConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        PromoInfoBlockRepositoryInterface $blockRepository,
        ProductResolver $productResolver,
        HttpContext $httpContext,
        ViewConfig $viewConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->blockRepository = $blockRepository;
        $this->productResolver = $productResolver;
        $this->httpContext = $httpContext;
        $this->viewConfig = $viewConfig;
    }

    /**
     * Create popup block
     *
     * @param PromoInfoBlockInterface $promoInfoBlock
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createPopup($promoInfoBlock)
    {
        /** @var Popup $popup */
        $popup = $this->getLayout()->createBlock(Popup::class);
        $popup->setPromoInfoBlock($promoInfoBlock);
        $popup->setPlacement($this->getPlacement());

        return $popup->toHtml();
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!empty($this->resolveProduct()) && !empty($this->getPromoInfoBlock())) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Retrieve promo block
     *
     * @return PromoInfoBlockInterface[]
     */
    public function getPromoInfoBlock()
    {
        $product = $this->resolveProduct();
        $customerGroupId = $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);

        $cacheKey = implode('-', [$this->getPlacement(), $product->getId(), $customerGroupId]);
        if (!isset($this->labelBlocks[$cacheKey])) {
            $promoInfoBlockArray = $this->blockRepository->getList($product, $customerGroupId);
            $this->promoInfoBlocks[$cacheKey] = reset($promoInfoBlockArray);
        }
        return $this->promoInfoBlocks[$cacheKey];
    }

    /**
     * Prepare promo config
     *
     * @return string
     */
    public function getPromoConfig()
    {
        return json_encode($this->viewConfig->getPromoConfig($this->getPlacement()));
    }

    /**
     * Resolve product
     *
     * @return Product|ProductInterface|null
     */
    private function resolveProduct()
    {
        return $this->getProduct() ? : $this->productResolver->resolveByPlacement($this->getPlacement());
    }
}

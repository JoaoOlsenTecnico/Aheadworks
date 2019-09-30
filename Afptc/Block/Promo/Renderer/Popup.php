<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Promo\Renderer;

use Aheadworks\Afptc\Api\Data\PromoInfoBlockInterface;
use Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\LayoutProcessorInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Popup
 *
 * @method Popup setPromoInfoBlock(PromoInfoBlockInterface $promoInfoBlock)
 * @method PromoInfoBlockInterface getPromoInfoBlock()
 * @method Popup setPlacement(string $placement)
 * @method string getPlacement()
 * @package Aheadworks\Afptc\Block\Label\Renderer
 */
class Popup extends Template
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'Aheadworks_Afptc::promo/renderer/popup.phtml';

    /**
     * @var LayoutProcessorInterface[]
     */
    private $layoutProcessors;

    /**
     * @var string
     */
    private $uId;

    /**
     * @param Context $context
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $layoutProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->layoutProcessors = $layoutProcessors;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout'])
            ? $data['jsLayout']
            : [];
        $this->uId = uniqid();
    }

    /**
     * {@inheritdoc}
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process(
                $this->jsLayout,
                $this->getPromoInfoBlock(),
                $this->getPlacement(),
                $this->getScope()
            );
        }

        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Retrieve data role
     *
     * @return string
     */
    public function getDataRole()
    {
        return 'aw-afptc-popup-' . $this->uId;
    }

    /**
     * Retrieve scope
     *
     * @return string
     */
    public function getScope()
    {
        return 'awAfptcPopup' . $this->uId;
    }
}

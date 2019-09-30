<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Sales\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject\Factory;
use Magento\Sales\Model\Order;
use Aheadworks\Afptc\Api\Data\OrderInterface;

/**
 * Class Total
 *
 * @package Aheadworks\Afptc\Block\Sales\Order
 */
class Total extends Template
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Context $context
     * @param Factory $factory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $factory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->factory = $factory;
    }

    /**
     * Init totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $source = $this->getSource();
        if (!$source) {
            return $this;
        }

        if ($source->getBaseAwAfptcAmount()) {
            $label = $source->getAwAfptcUsesCoupon()
                ? __('Promo Discount (%1)', $source->getCouponCode())
                : __('Promo Discount');
            $this->getParentBlock()->addTotal(
                $this->factory->create(
                    [
                        'code'   => OrderInterface::AW_AFPTC_AMOUNT,
                        'strong' => false,
                        'label'  => $label,
                        'value'  => $source->getAwAfptcAmount(),
                    ]
                )
            );
        }

        return $this;
    }

    /**
     * Retrieve totals source object
     *
     * @return Order|null
     */
    private function getSource()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            return $parentBlock->getSource();
        }
        return null;
    }
}

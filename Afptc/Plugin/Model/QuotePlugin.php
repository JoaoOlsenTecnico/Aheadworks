<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Plugin\Model;

use Aheadworks\Afptc\Api\Data\CartItemInterface;
use Aheadworks\Afptc\Model\Cart\Item\ExtensionAttributes\Processor\Composite;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Aheadworks\Afptc\Model\Cart\Converter\InfoBuyRequest as RequestConverter;

/**
 * Class QuotePlugin
 *
 * @package Aheadworks\Afptc\Plugin\Model
 */
class QuotePlugin
{
    /**
     * @var Composite
     */
    private $processor;

    /**
     * @var RequestConverter
     */
    private $requestConverter;

    /**
     * @param Composite $processor
     * @param RequestConverter $requestConverter
     */
    public function __construct(
        Composite $processor,
        RequestConverter $requestConverter
    ) {
        $this->processor = $processor;
        $this->requestConverter = $requestConverter;
    }

    /**
     * Convert quote items data after load quote items
     *
     * @param Quote $subject
     * @param CartItemInterface[]|Item[] $result
     * @return CartItemInterface[]|Item[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAllVisibleItems($subject, $result)
    {
        foreach ($result as $item) {
            $this->processor->prepareDataAfterLoad($item);
        }

        return $result;
    }

    /**
     * Set promo flag to product if needed
     *
     * @param Quote $subject
     * @param Product $product
     * @param DataObject|float $request
     * @param string $processMode
     */
    public function beforeAddProduct(
        $subject,
        $product,
        $request = null,
        $processMode = AbstractType::PROCESS_MODE_FULL
    ) {
        if ($request instanceof DataObject && $request->getAwAfptcIsPromo()) {
            $product->setAwAfptcIsPromo($request->getAwAfptcIsPromo());
            $request->setAwAfptcRules(
                $this->requestConverter->convertToDataModel($request->getAwAfptcRules())
            );
        }
    }
}

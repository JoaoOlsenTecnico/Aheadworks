<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Aheadworks\Afptc\Api\Data\PromoOfferRender\ProductRenderInterface;
use Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor\QuantityNotice\Resolver as NoticeResolver;

/**
 * Class QuantityNotice
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class QuantityNotice implements ProcessorInterface
{
    /**
     * @var NoticeResolver
     */
    private $noticeResolver;

    /**
     * @param NoticeResolver $noticeResolver
     */
    public function __construct(
        NoticeResolver $noticeResolver
    ) {
        $this->noticeResolver = $noticeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        $items = $promoOfferRender->getItems();
        $promoOfferRender->setIsQuantityNoticeActive($this->checkIfNoticeIsVisible($items));
    }

    /**
     * Check if notice is visible
     *
     * @param ProductRenderInterface[] $items
     * @return bool
     */
    private function checkIfNoticeIsVisible($items)
    {
        foreach ($items as $item) {
            if ($this->noticeResolver->isNeedToDisplayForItem($item)) {
                return true;
            }
        }
        return false;
    }
}

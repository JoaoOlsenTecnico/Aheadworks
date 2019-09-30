<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url;

use Magento\Framework\UrlInterface;

/**
 * Class Resolver
 *
 * @package Aheadworks\Afptc\Model\Rule\Promo\Block\Layout\Processor\Url
 */
class Resolver
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param string $url
     * @return string
     */
    public function resolve($url)
    {
        return $url
            ? $this->urlBuilder->getUrl(ltrim($url, '/'))
            : null;
    }
}

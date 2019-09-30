<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule;

use Magento\Framework\UrlInterface;

/**
 * Class FormConfig
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule
 */
class FormConfig
{
    /**
     * Submit url key
     */
    const KEY_SUBMIT_URL = 'submit_url';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $productUrls = [
        self::KEY_SUBMIT_URL => 'aw_afptc/rule/save',
    ];

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare form config
     *
     * @param $storeId
     * @return array
     */
    public function prepareConfig($storeId)
    {
        $parameters = [
            'store' => $storeId,
        ];

        $submitUrl = $this->urlBuilder->getUrl($this->productUrls[self::KEY_SUBMIT_URL], $parameters);

        return [
            self::KEY_SUBMIT_URL => $submitUrl
        ];
    }
}

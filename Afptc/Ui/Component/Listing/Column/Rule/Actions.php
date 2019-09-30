<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\Component\Listing\Column\Rule;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Actions
 *
 * @package Aheadworks\Afptc\Ui\Component\Listing\Column\Rule
 */
class Actions extends Column
{
    /**#@+
     * Url path
     */
    const URL_PATH_EDIT = 'aw_afptc/rule/edit';
    const URL_PATH_DELETE = 'aw_afptc/rule/delete';
    /**#@-*/

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[RuleInterface::RULE_ID])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                self::URL_PATH_EDIT,
                                [
                                    'id' => $item[RuleInterface::RULE_ID]
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                self::URL_PATH_DELETE,
                                [
                                    'id' => $item[RuleInterface::RULE_ID]
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete item'),
                                'message' => __('Are you sure you want to delete selected item?')
                            ]
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}

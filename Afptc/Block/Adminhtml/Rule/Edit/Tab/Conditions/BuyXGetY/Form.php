<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\BuyXGetY;

use Magento\Rule\Block\Conditions as ConditionsBlock;
use Magento\Backend\Block\Widget\Form\Renderer\FieldsetFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\BuyXGetYFactory;
use Magento\Framework\UrlInterface;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\BuyXGetY;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\AbstractForm;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\DataProvider;

/**
 * Class Form
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\BuyXGetY
 */
class Form extends AbstractForm
{
    /**#@+
     * Constants defined for form with conditions for buy x form
     */
    const FORM_FIELDSET_NAME = 'buy_x_conditions_fieldset';
    const CONDITION_FIELD_NAME = BuyXGetY::CONDITION_PREFIX;
    /**#@-*/

    /**
     * @param ConditionsBlock $conditions
     * @param FieldsetFactory $rendererFieldsetFactory
     * @param BuyXGetYFactory $cartRule
     * @param DataProvider $formDataProvider
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConditionsBlock $conditions,
        FieldsetFactory $rendererFieldsetFactory,
        BuyXGetYFactory $cartRule,
        DataProvider $formDataProvider,
        UrlInterface $urlBuilder
    ) {
        $this->rendererFieldsetFactory = $rendererFieldsetFactory;
        $this->conditions = $conditions;
        $this->cartRule = $cartRule;
        $this->formDataProvider = $formDataProvider;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve renderer for form fieldset
     *
     * @return RendererInterface
     */
    protected function getFieldsetRenderer()
    {
        return $this->rendererFieldsetFactory->create()
            ->setTemplate($this->fieldsetTemplate)
            ->setNewChildUrl(
                $this->urlBuilder->getUrl(
                    static::NEW_CHILD_URL_ROUTE,
                    [
                        'form'   => static::FORM_ID_PREFIX . static::FORM_FIELDSET_NAME,
                        'prefix' => BuyXGetY::CONDITION_PREFIX,
                        'rule'   => base64_encode(BuyXGetY::class),
                        'form_namespace' => static::FORM_NAME
                    ]
                )
            );
    }
}

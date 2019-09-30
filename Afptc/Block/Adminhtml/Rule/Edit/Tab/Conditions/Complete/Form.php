<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\Complete;

use Magento\Rule\Block\Conditions as ConditionsBlock;
use Magento\Backend\Block\Widget\Form\Renderer\FieldsetFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\CompleteFactory;
use Magento\Framework\UrlInterface;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\Rule\Complete;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\DataProvider;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\AbstractForm;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

/**
 * Class Form
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\Complete
 */
class Form extends AbstractForm
{
    /**#@+
     * Constants defined for form with conditions for complete form
     */
    const FORM_FIELDSET_NAME = 'complete_conditions_fieldset';
    const CONDITION_FIELD_NAME = Complete::CONDITION_PREFIX;
    /**#@-*/

    /**
     * @param ConditionsBlock $conditions
     * @param FieldsetFactory $rendererFieldsetFactory
     * @param CompleteFactory $cartRule
     * @param DataProvider $formDataProvider
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConditionsBlock $conditions,
        FieldsetFactory $rendererFieldsetFactory,
        CompleteFactory $cartRule,
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
                        'prefix' => Complete::CONDITION_PREFIX,
                        'rule'   => base64_encode(Complete::class),
                        'form_namespace' => static::FORM_NAME
                    ]
                )
            );
    }
}

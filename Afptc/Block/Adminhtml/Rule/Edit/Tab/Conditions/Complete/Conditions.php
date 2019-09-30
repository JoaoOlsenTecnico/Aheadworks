<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\Complete;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Block\Widget\Form\Renderer\FieldsetFactory;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\AbstractConditions;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\AbstractForm;

/**
 * Class Conditions
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\Complete
 */
class Conditions extends AbstractConditions
{
    /**
     * @var string
     */
    protected $_nameInLayout = 'advanced_conditions_complete';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Form $form
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Form $form,
        array $data = []
    ) {
        $this->form = $form;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Create form for controls
     *
     * @return \Magento\Framework\Data\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function createForm()
    {
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix(AbstractForm::FORM_ID_PREFIX);
        return $form;
    }
}

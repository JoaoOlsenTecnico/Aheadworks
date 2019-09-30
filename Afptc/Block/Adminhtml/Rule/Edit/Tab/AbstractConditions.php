<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\FieldsetFactory;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions\AbstractForm;

/**
 * Class AbstractConditions
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab
 */
class AbstractConditions extends Generic implements TabInterface
{
    /**
     * @var AbstractForm
     */
    protected $form;

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getTabClass()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabUrl()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Conditions');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Conditions');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareForm()
    {
        $form = $this->createForm();
        $this->form->prepareForm($form);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}

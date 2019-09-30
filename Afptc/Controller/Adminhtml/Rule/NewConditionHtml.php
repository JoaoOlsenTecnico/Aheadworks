<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule;

use Magento\SalesRule\Controller\Adminhtml\Promo\Quote;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\SalesRule\Model\Rule;

/**
 * Class NewConditionHtml
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule
 */
class NewConditionHtml extends Quote
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Afptc::rules';

    /**
     * New condition html action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $formName = $this->getRequest()->getParam('form_namespace');

        $prefix = 'conditions';
        if ($this->getRequest()->getParam('prefix')) {
            $prefix = $this->getRequest()->getParam('prefix');
        }

        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = $this->_objectManager
            ->create($type)
            ->setId($id)
            ->setType($type)
            ->setRule($this->_objectManager->create(Rule::class))
            ->setPrefix($prefix);

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $model->setFormName($formName);
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}

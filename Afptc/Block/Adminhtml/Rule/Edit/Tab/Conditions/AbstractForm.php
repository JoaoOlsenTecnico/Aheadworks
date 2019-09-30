<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions;

use Magento\Framework\Data\Form as DataForm;
use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Rule\Block\Conditions as ConditionsBlock;
use Magento\Backend\Block\Widget\Form\Renderer\FieldsetFactory;
use Aheadworks\Afptc\Model\Rule\Condition\Cart\AbstractCart as CartRuleFactory;
use Magento\Framework\UrlInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\Rule\Model\Condition\AbstractCondition;

/**
 * Class AbstractForm
 *
 * @package Aheadworks\Afptc\Block\Adminhtml\Rule\Edit\Tab\Conditions
 */
class AbstractForm
{
    /**#@+
     * Constants defined for form with conditions for abstract form
     */
    const FORM_NAME = 'aw_afptc_rule_form';
    const FORM_ID_PREFIX = 'rule_';
    const NEW_CHILD_URL_ROUTE = '*/*/newConditionHtml';
    /**#@-*/

    /**
     * @var ConditionsBlock
     */
    protected $conditions;

    /**
     * @var FieldsetFactory
     */
    protected $rendererFieldsetFactory;

    /**
     * @var array
     */
    protected $formData;

    /**
     * @var CartRuleFactory
     */
    protected $cartRule;

    /**
     * @var DataProvider
     */
    protected $formDataProvider;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    protected $fieldsetTemplate = 'Magento_CatalogRule::promo/fieldset.phtml';

    /**
     * Prepare form
     *
     * @param DataForm $form
     */
    public function prepareForm($form)
    {
        $fieldset = $this->addFieldsetToForm($form);
        $this->prepareFieldset($fieldset);
    }

    /**
     * Add fieldset to specified form
     *
     * @param DataForm $form
     * @return Fieldset
     */
    protected function addFieldsetToForm($form)
    {
        return $form->addFieldset(static::FORM_FIELDSET_NAME, []);
    }

    /**
     * Prepare field set for form
     *
     * @param Fieldset $fieldset
     */
    protected function prepareFieldset($fieldset)
    {
        $conditionData = $this->formDataProvider->getConditions(static::CONDITION_FIELD_NAME);
        $conditionRule = $this->getConditionRule($conditionData);
        $fieldset->setRenderer($this->getFieldsetRenderer());
        $conditionRule->setJsFormObject(static::FORM_ID_PREFIX . static::FORM_FIELDSET_NAME);
        $this->addFieldsToFieldset($fieldset, $conditionRule);
        $this->setConditionFormName(
            $conditionRule->getConditions(),
            static::FORM_NAME,
            static::FORM_ID_PREFIX . static::FORM_FIELDSET_NAME
        );
    }

    /**
     * Retrieve condition rule object from condition array
     *
     * @param mixed $conditionData
     * @return AbstractModel
     */
    protected function getConditionRule($conditionData)
    {
        $cartRule = $this->cartRule->create();
        if (isset($conditionData) && (is_array($conditionData))) {
            $cartRule->setConditions([])
                ->getConditions()
                ->loadArray($conditionData);
        }
        return $cartRule;
    }

    /**
     * Add necessary fields to form fieldset
     *
     * @param Fieldset $fieldset
     * @param mixed $conditionData
     */
    protected function addFieldsToFieldset($fieldset, $conditionData)
    {
        $fieldset
            ->addField(
                static::CONDITION_FIELD_NAME,
                'text',
                [
                    'name' => static::CONDITION_FIELD_NAME,
                    'label' => __('Conditions'),
                    'title' => __('Conditions'),
                    'data-form-part' => static::FORM_NAME
                ]
            )
            ->setRule($conditionData)
            ->setRenderer($this->conditions);
    }

    /**
     * Handles addition of form name to condition and its conditions
     *
     * @param AbstractCondition $conditions
     * @param string $formName
     * @param string $jsFormObject
     * @return void
     */
    protected function setConditionFormName($conditions, $formName, $jsFormObject)
    {
        $conditions->setFormName($formName);
        $conditions->setJsFormObject($jsFormObject);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName, $jsFormObject);
            }
        }
    }
}

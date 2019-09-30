<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Validator;

use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Date
 *
 * @package Aheadworks\Afptc\Model\Rule\Validator
 */
class Date extends AbstractValidator
{
    /**
     * Returns true from date is less then to date
     *
     * @param RuleInterface $rule
     * @return bool
     */
    public function isValid($rule)
    {
        $fromDate = $rule->getFromDate();
        $toDate = $rule->getToDate();

        if ($fromDate && $toDate) {
            $fromDate = new \DateTime($fromDate);
            $toDate = new \DateTime($toDate);

            if ($fromDate > $toDate) {
                $this->_addMessages([__('End Date must follow Start Date.')]);
            }
        }

        return empty($this->getMessages());
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\PromoOffer\Validator;

/**
 * Class Pool
 *
 * @package Aheadworks\Afptc\Model\Rule\PromoOffer\Validator
 */
class Pool
{
    /**
     * @var array
     */
    private $validators = [];

    /**
     * @param array $validators
     */
    public function __construct($validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * Retrieves validator by rule scenarios
     *
     * @param string $ruleScenarios
     * @return ValidatorInterface
     * @throws \Exception
     */
    public function getValidator($ruleScenarios)
    {
        if (!isset($this->validators[$ruleScenarios])) {
            throw new \Exception(sprintf('Unknown validator: %s requested', $ruleScenarios));
        }
        return $this->validators[$ruleScenarios];
    }
}

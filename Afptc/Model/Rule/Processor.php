<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule;

use Aheadworks\Afptc\Model\Rule as RuleModel;

/**
 * Class Processor
 *
 * @package Aheadworks\Afptc\Model\Rule
 */
class Processor
{
    /**
     * @var array[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * Prepare entity data before save
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function prepareDataBeforeSave($rule)
    {
        foreach ($this->processors as $processor) {
            $processor->beforeSave($rule);
        }
        return $rule;
    }

    /**
     * Prepare entity data after load
     *
     * @param RuleModel $rule
     * @return RuleModel
     */
    public function prepareDataAfterLoad($rule)
    {
        foreach ($this->processors as $processor) {
            $processor->afterLoad($rule);
        }
        return $rule;
    }
}

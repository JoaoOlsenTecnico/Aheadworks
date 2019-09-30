<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date as DateFilter;

/**
 * Class Date
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class Date implements ProcessorInterface
{
    /**
     * @var DateFilter
     */
    private $dateFilter;

    /**
     * @param DateFilter $dateFilter
     */
    public function __construct(
        DateFilter $dateFilter
    ) {
        $this->dateFilter = $dateFilter;
    }

    /**
     * Prepare dates for save
     *
     * @param array $data
     * @return array
     */
    public function process($data)
    {
        $filterValues = [];
        if (empty($data[RuleInterface::FROM_DATE])) {
            $data[RuleInterface::FROM_DATE] = null;
        } else {
            $filterValues[RuleInterface::FROM_DATE] = $this->dateFilter;
        }
        if (empty($data[RuleInterface::TO_DATE])) {
            $data[RuleInterface::TO_DATE] = null;
        } else {
            $filterValues[RuleInterface::TO_DATE] = $this->dateFilter;
        }

        $inputFilter = new \Zend_Filter_Input(
            $filterValues,
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        return $data;
    }
}

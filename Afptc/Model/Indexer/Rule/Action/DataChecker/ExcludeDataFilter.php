<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker;

/**
 * Class ExcludeDataFilter
 *
 * @package Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker
 */
class ExcludeDataFilter
{
    /**
     * @var array
     */
    private $fields;

    /**
     * @param array $fields
     */
    public function __construct(
        array $fields = []
    ) {
        $this->fields = $fields;
    }

    /**
     * Exclude data from array
     *
     * @param array $data
     * @return array
     */
    public function apply($data)
    {
        $result = $data;
        foreach ($this->fields as $name => $field) {
            unset($result[$field]);
        }

        return $result;
    }
}

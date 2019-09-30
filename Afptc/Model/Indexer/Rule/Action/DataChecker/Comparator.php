<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker;

/**
 * Class Comparator
 *
 * @package Aheadworks\Afptc\Model\Indexer\Rule\Action\DataChecker
 */
class Comparator
{
    /**
     * Get array with data differences
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public function findDiffInArrays($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            if (array_key_exists($key, $array2)) {
                if ($value != $array2[$key]) {
                    $result[$key] = true;
                }
            } else {
                $result[$key] = true;
            }
        }
        return $result;
    }
}

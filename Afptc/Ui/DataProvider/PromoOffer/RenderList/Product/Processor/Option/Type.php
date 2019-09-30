<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option;

/**
 * Class Type
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Product\Processor\Option
 */
class Type
{
    /**#@+
     * Constants defined for configuration types
     */
    const NO_OPTION = 'no_option';
    const CONFIGURABLE_OPTION = 'configurable_option';
    const PRE_CONFIGURED_OPTION = 'pre_configured_option';
    /**#@-*/

    /**
     * Get link text by option type
     *
     * @param string $optionType
     * @return string
     */
    public function getTextByOptionType($optionType)
    {
        $optionTypeTextMap = $this->getOptionTypeTextArray();
        return $optionTypeTextMap[$optionType];
    }

    /**
     * Get strings for every option type
     *
     * @return array
     */
    private function getOptionTypeTextArray()
    {
        return [
            self::NO_OPTION => '',
            self::CONFIGURABLE_OPTION  => __('Configure'),
            self::PRE_CONFIGURED_OPTION => __('View Details')
        ];
    }
}

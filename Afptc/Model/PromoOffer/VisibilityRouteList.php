<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\PromoOffer;

/**
 * Class VisibilityRouteList
 *
 * @package Aheadworks\Afptc\Model\PromoOffer
 */
class VisibilityRouteList
{
    /**
     * @var array
     */
    private $types;

    /**
     * @param array $types
     */
    public function __construct(
        $types = []
    ) {
        $this->types = $types;
    }

    /**
     * Get routes
     *
     * Popup is visible only for provided routes, otherwise it's shown up everywhere if none provided
     *
     * @param string $type
     * @return array
     */
    public function getRoutes($type)
    {
        $resultRoutes = [];
        if (isset($this->types[$type])) {
            $resultRoutes = $this->types[$type];
        }

        return $resultRoutes;
    }
}

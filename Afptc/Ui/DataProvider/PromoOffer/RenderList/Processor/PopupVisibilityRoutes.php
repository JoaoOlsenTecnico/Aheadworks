<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor;

use Aheadworks\Afptc\Model\Config;
use Aheadworks\Afptc\Model\PromoOffer\VisibilityRouteList;

/**
 * Class PopupVisibilityRoutes
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\PromoOffer\RenderList\Processor
 */
class PopupVisibilityRoutes implements ProcessorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var VisibilityRouteList
     */
    private $routeList;

    /**
     * @param Config $config
     * @param VisibilityRouteList $routeList
     */
    public function __construct(
        Config $config,
        VisibilityRouteList $routeList
    ) {
        $this->config = $config;
        $this->routeList = $routeList;
    }

    /**
     * @inheritdoc
     */
    public function prepareRender($promoOfferRender, $metadataRules)
    {
        $type = $this->config->getWhereToDisplayPopupType();
        $routes = $this->routeList->getRoutes($type);
        $promoOfferRender->setPageRoutesToDisplayPopup($routes);
    }
}

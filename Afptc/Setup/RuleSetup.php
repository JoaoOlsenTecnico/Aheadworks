<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Setup;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Eav\Setup\EavSetup;
use Aheadworks\Afptc\Model\Rule as RuleModel;
use Aheadworks\Afptc\Model\ResourceModel\Rule as RuleResource;

/**
 * Class PromoSetup
 *
 * @package Aheadworks\Afptc\Setup
 */
class RuleSetup extends EavSetup
{
    public function getDefaultEntities()
    {
        $promoEntity = RuleModel::ENTITY;
        $entities = [
            $promoEntity => [
                'entity_model' => RuleResource::class,
                'table' => RuleResource::MAIN_TABLE_NAME,
                'id_field' => RuleInterface::RULE_ID,
                'attributes' => [
                    'name' => [
                        'type' => 'static',
                    ],
                    'description' => [
                        'type' => 'static',
                    ],
                    'active' => [
                        'type' => 'static',
                    ],
                    'from_date' => [
                        'type' => 'static',
                    ],
                    'to_date' => [
                        'type' => 'static',
                    ],
                    'priority' => [
                        'type' => 'static',
                    ],
                    'stop_rules_processing' => [
                        'type' => 'static',
                    ],
                    'in_stock_offer_only' => [
                        'type' => 'static',
                    ],
                    'scenario' => [
                        'type' => 'static',
                    ],
                    'cart_conditions' => [
                        'type' => 'static',
                    ],
                    'simple_action' => [
                        'type' => 'static',
                    ],
                    'simple_action_n' => [
                        'type' => 'static',
                    ],
                    'qty_to_give' => [
                        'type' => 'static',
                    ],
                    'discount_amount' => [
                        'type' => 'static',
                    ],
                    'discount_type' => [
                        'type' => 'static',
                    ],
                    'coupon_code' => [
                        'type' => 'static',
                    ],
                    'coupon_id' => [
                        'type' => 'static',
                    ],
                    'how_to_offer' => [
                        'type' => 'static',
                    ],
                ],
            ],
        ];
        return $entities;
    }

    /**
     * Retrieve attributes config to install
     *
     * @return array
     */
    public function getAttributesToInstall()
    {
        $attributes = [
            [
                'attribute' => RuleInterface::POPUP_HEADER_TEXT,
                'params' => ['type' => 'text']
            ],
            [
                'attribute' => RuleInterface::PROMO_OFFER_INFO_TEXT,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_ON_SALE_LABEL_ID,
                'params' => ['type' => 'int']
            ],
            [
                'attribute' => 'promo_on_sale_label_text',
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_ON_SALE_LABEL_TEXT_MEDIUM,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_ON_SALE_LABEL_TEXT_SMALL,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_IMAGE,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_IMAGE_ALT_TEXT,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_HEADER,
                'params' => ['type' => 'varchar']
            ],
            [
                'attribute' => RuleInterface::PROMO_DESCRIPTION,
                'params' => ['type' => 'text']
            ],
            [
                'attribute' => RuleInterface::PROMO_URL,
                'params' => ['type' => 'text']
            ],
            [
                'attribute' => RuleInterface::PROMO_URL_TEXT,
                'params' => ['type' => 'text']
            ],
        ];

        return $attributes;
    }
}

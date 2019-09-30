<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\ThirdPartyModule\OnSale\Source\Label\LabelList;

/**
 * Class OnSaleLabelId
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class OnSaleLabelId implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        if (isset($data[RuleInterface::PROMO_ON_SALE_LABEL_ID])) {
            $data[RuleInterface::PROMO_ON_SALE_LABEL_ID] =
                $data[RuleInterface::PROMO_ON_SALE_LABEL_ID] == LabelList::DO_NOT_USE
                    ? null
                    : $data[RuleInterface::PROMO_ON_SALE_LABEL_ID];
        }

        return $data;
    }
}

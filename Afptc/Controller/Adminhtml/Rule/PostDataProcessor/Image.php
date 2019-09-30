<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor;

use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Image
 *
 * @package Aheadworks\Afptc\Controller\Adminhtml\Rule\PostDataProcessor
 */
class Image implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        if (isset($data[RuleInterface::PROMO_IMAGE])) {
            $data[RuleInterface::PROMO_IMAGE] = $data[RuleInterface::PROMO_IMAGE][0]['file'];
        } else {
            $data[RuleInterface::PROMO_IMAGE] = '';
        }

        return $data;
    }
}

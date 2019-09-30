<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Source\Customer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\ResourceModel\Group\Collection;

/**
 * Class Group
 *
 * @package Aheadworks\Afptc\Model\Source\Customer
 */
class Group implements OptionSourceInterface
{
    /**
     * @var Collection
     */
    private $customerGroupCollection;

    /**
     * @param Collection $customerGroupCollection
     */
    public function __construct(
        Collection $customerGroupCollection
    ) {
        $this->customerGroupCollection = $customerGroupCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $customerGroups = $this->customerGroupCollection->toOptionArray();
        return $customerGroups;
    }
}

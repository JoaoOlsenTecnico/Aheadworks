<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\ResourceModel\Report;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Aheadworks\Afptc\Model\Source\Rule\Status;
use Aheadworks\Afptc\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Framework\DB\Select;

/**
 * Class Rule
 *
 * @package Aheadworks\Afptc\Model\ResourceModel\Report
 */
class Rule extends RuleCollection
{
    /**
     * Retrieve rule statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        $this->getSelect()->reset(Select::COLUMNS);
        $this->getSelect()
            ->columns(
                [
                    'active_promo_campaigns' => new \Zend_Db_Expr('COUNT(*)')
                ]
            )->where(RuleInterface::IS_ACTIVE . ' = ?', Status::ENABLED);

        $this->_renderFilters()->_renderOrders()->_renderLimit();
        return $this->getConnection()->fetchRow($this->getSelect());
    }
}

<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api;

/**
 * Interface IndexManagementInterface
 * @api
 */
interface IndexManagementInterface
{
    /**
     * Check if rule data has been changed and reset index if necessary
     *
     * @param \Aheadworks\Afptc\Api\Data\RuleInterface $newRuleModel
     * @param \Aheadworks\Afptc\Api\Data\RuleInterface $oldRuleModel
     * @return bool
     */
    public function invalidateIndexOnDataChange($newRuleModel, $oldRuleModel);
}

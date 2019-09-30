<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty;

/**
 * Class ScenarioPool
 *
 * @package Aheadworks\Afptc\Model\Rule\Converter\ToMetadataRule\Resolver\Qty
 */
class ScenarioPool
{
    /**
     * @var array
     */
    private $scenarios = [];

    /**
     * @param array $scenarios
     */
    public function __construct(
        $scenarios = []
    ) {
        $this->scenarios = $scenarios;
    }

    /**
     * Get scenario processor instance
     *
     * @param string $scenario
     * @return ScenarioInterface
     * @throws \Exception
     */
    public function getScenarioProcessor($scenario)
    {
        if (!isset($this->scenarios[$scenario])) {
            throw new \Exception(sprintf('Unknown scenario: %s requested', $scenario));
        }
        $scenarioInstance = $this->scenarios[$scenario];
        if (!$scenarioInstance instanceof ScenarioInterface) {
            throw new \Exception(
                sprintf('Scenario instance %s does not implement required interface.', $scenario)
            );
        }

        return $scenarioInstance;
    }
}

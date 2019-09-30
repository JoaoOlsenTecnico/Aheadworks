<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Cart\Item\Renderer;

use Aheadworks\Afptc\Api\Data\RuleInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Aheadworks\Afptc\Api\RuleRepositoryInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator\Pool as CalculatorPool;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterfaceFactory;
use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class PriceAdjustment
 *
 * @package Aheadworks\Afptc\Model\Cart\Item\Renderer
 */
class PriceAdjustment
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var CalculatorPool
     */
    private $calculatorPool;

    /**
     * @var RuleMetadataInterfaceFactory
     */
    private $ruleMetadataFactory;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param CalculatorPool $calculatorPool
     * @param RuleMetadataInterfaceFactory $ruleMetadataFactory
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        CalculatorPool $calculatorPool,
        RuleMetadataInterfaceFactory $ruleMetadataFactory
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->calculatorPool = $calculatorPool;
        $this->ruleMetadataFactory = $ruleMetadataFactory;
    }

    /**
     * Update prices in quote item for proper price rendering
     *
     * @param AbstractItem $quoteItem
     */
    public function adjustForRendering($quoteItem)
    {
        if ($quoteItem->getAwAfptcIsPromo()) {
            $rule = $this->getCurrentRule($quoteItem);
            if ($rule) {
                /** @var RuleMetadataInterface $metadataRule */
                $metadataRule = $this->ruleMetadataFactory->create();
                $metadataRule
                    ->setRule($rule)
                    ->setQuoteItem($quoteItem);
                $calculator = $this->calculatorPool->getCalculatorByType($rule->getDiscountType());

                $quoteItem->setPriceInclTax(
                    $calculator->calculatePrice($quoteItem->getPriceInclTax(), $metadataRule)
                );
                $quoteItem->setBasePriceInclTax(
                    $calculator->calculatePrice($quoteItem->getBasePriceInclTax(), $metadataRule)
                );

                $quoteItem->setRowTotalInclTax(
                    $calculator->calculatePrice($quoteItem->getRowTotalInclTax(), $metadataRule)
                );
                //todo we have to store original row total for validation till new price logic is implemented M2APTC-267
                $quoteItem->setOrigBaseRowTotalInclTax($quoteItem->getBaseRowTotalInclTax());
                $quoteItem->setBaseRowTotalInclTax(
                    $calculator->calculatePrice($quoteItem->getBaseRowTotalInclTax(), $metadataRule)
                );
                $quoteItem->setCalculationPrice(
                    $calculator->calculatePrice($quoteItem->getCalculationPrice(), $metadataRule)
                );

                $quoteItem->setPrice($calculator->calculatePrice($quoteItem->getPrice(), $metadataRule));
                $quoteItem->setBasePrice($calculator->calculatePrice($quoteItem->getBasePrice(), $metadataRule));
                $quoteItem->setRowTotal(
                    $calculator->calculatePrice($quoteItem->getRowTotal(), $metadataRule)
                );
                $quoteItem->setOrigBaseRowTotal($quoteItem->getBaseRowTotal());
                $quoteItem->setBaseRowTotal(
                    $calculator->calculatePrice($quoteItem->getBaseRowTotal(), $metadataRule)
                );
            }
        }
    }

    /**
     * Get current promo rule
     *
     * @param AbstractItem $quoteItem
     * @return RuleInterface|null
     */
    private function getCurrentRule($quoteItem)
    {
        try {
            $appliedRuleIds = explode(",", $quoteItem->getAwAfptcRuleIds());
            $firstAppliedRule = reset($appliedRuleIds);
            $rule = $this->ruleRepository->get($firstAppliedRule);
        } catch (NoSuchEntityException $exception) {
            $rule = null;
        }

        return $rule;
    }
}

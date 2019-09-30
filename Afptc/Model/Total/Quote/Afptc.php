<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Total\Quote;

use Aheadworks\Afptc\Api\Data\RuleMetadataInterface;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Manager as RuleManager;
use Aheadworks\Afptc\Model\Rule\PromoOffer\Quote\Address\Modifier;
use Aheadworks\Afptc\Model\Source\Rule\Scenario;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Aheadworks\Afptc\Model\Rule\Discount\Calculator as RuleCalculator;
use Aheadworks\Afptc\Model\Rule\Discount\ItemsApplier as RuleItemsApplier;
use Aheadworks\Afptc\Model\Metadata\Rule\Discount as MetadataRuleDiscount;

/**
 * Class Afptc
 *
 * @package Aheadworks\Afptc\Model\Total\Quote
 */
class Afptc extends AbstractTotal
{
    /**
     * @var RuleManager
     */
    private $ruleManager;

    /**
     * @var RuleCalculator
     */
    private $ruleCalculator;

    /**
     * @var RuleItemsApplier
     */
    private $ruleItemsApplier;

    /**
     * @var Modifier
     */
    private $addressModifier;

    /**
     * @var bool
     */
    private $isFirstTimeResetRun = true;

    /**
     * @param RuleManager $ruleManager
     * @param RuleCalculator $ruleCalculator
     * @param RuleItemsApplier $ruleItemsApplier
     * @param Modifier $addressModifier
     */
    public function __construct(
        RuleManager $ruleManager,
        RuleCalculator $ruleCalculator,
        RuleItemsApplier $ruleItemsApplier,
        Modifier $addressModifier
    ) {
        $this->setCode('aw_afptc');
        $this->ruleManager = $ruleManager;
        $this->ruleCalculator = $ruleCalculator;
        $this->ruleItemsApplier = $ruleItemsApplier;
        $this->addressModifier = $addressModifier;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        $this->reset($quote, $address, $items);

        if (!count($items)) {
            return $this;
        }

        $metadataRules = $this->ruleManager->getDiscountMetadataRules(
            $quote->getStore()->getStoreId(),
            $quote->getCustomerGroupId(),
            $this->addressModifier->modify($address, $quote, $total),
            $items
        );
        $metadataRuleDiscount = $this->ruleCalculator->calculateDiscount($items, $address, $metadataRules);
        if (!$metadataRuleDiscount->isDiscountAvailable()) {
            $this->reset($quote, $address, $items, true);
            return $this;
        }

        $this->ruleItemsApplier->apply($items, $metadataRuleDiscount);

        $address
            ->setAwAfptcAmount($total->getAwAfptcAmount())
            ->setBaseAwAfptcAmount($total->getBaseAwAfptcAmount());

        $this
            ->_addAmount(-$metadataRuleDiscount->getAmount())
            ->_addBaseAmount(-$metadataRuleDiscount->getBaseAmount());

        $total
            ->setSubtotalWithDiscount($total->getSubtotalWithDiscount() + $total->getAwAfptcAmount())
            ->setBaseSubtotalWithDiscount($total->getBaseSubtotalWithDiscount() + $total->getBaseAwAfptcAmount());

        $quote
            ->setAwAfptcAmount($total->getAwAfptcAmount())
            ->setBaseAwAfptcAmount($total->getBaseAwAfptcAmount());

        $quote->setAwAfptcUsesCoupon($this->isAfptcCouponUsedInQuote($quote, $metadataRules, $metadataRuleDiscount));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Quote $quote, Total $total)
    {
        $this->setCode('aw_afptc');
        $amount = $total->getAwAfptcAmount();
        if ($amount != 0) {
            return [
                'code' => $this->getCode(),
                'title' => __('Promo Discount'),
                'value' => $amount
            ];
        }

        return null;
    }

    /**
     * Reset totals
     *
     * @param Quote $quote
     * @param AddressInterface $address
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $items
     * @param bool $reset
     * @return $this
     */
    protected function reset(Quote $quote, AddressInterface $address, $items, $reset = false)
    {
        if ($this->isFirstTimeResetRun || $reset) {
            $this
                ->_addAmount(0)
                ->_addBaseAmount(0);

            $quote
                ->setAwAfptcAmount(0)
                ->setBaseAwAfptcAmount(0)
                ->setAwAfptcUsesCoupon(null);

            $address
                ->setAwAfptcAmount(0)
                ->setBaseAwAfptcAmount(0);

            /** @var \Magento\Quote\Model\Quote\Item $item */
            foreach ($items as $item) {
                $this->ruleItemsApplier->reset($item);
            }

            $this->isFirstTimeResetRun = false;
        }
        return $this;
    }

    /**
     * Check if afptc coupon used in quote
     *
     * @param Quote $quote
     * @param RuleMetadataInterface[] $metadataRules
     * @param MetadataRuleDiscount $metadataRuleDiscount
     * @return bool|null
     */
    private function isAfptcCouponUsedInQuote($quote, $metadataRules, $metadataRuleDiscount)
    {
        $result = null;
        if ($quote->getCouponCode()) {
            foreach ($metadataRules as $metadataRule) {
                $rule = $metadataRule->getRule();
                if ($rule->getScenario() == Scenario::COUPON
                    && $metadataRuleDiscount->getTotalQtyByRule($rule->getRuleId()) > 0
                ) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
}

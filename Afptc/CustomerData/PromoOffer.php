<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\CustomerData;

use Aheadworks\Afptc\Api\PromoOfferRenderListInterface;
use Aheadworks\Afptc\Api\RuleManagementInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\EntityManager\Hydrator;

/**
 * Class PromoProducts
 *
 * @package Aheadworks\Afptc\CustomerData
 */
class PromoOffer implements SectionSourceInterface
{
    /**
     * @var RuleManagementInterface
     */
    private $ruleManagement;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var PromoOfferRenderListInterface
     */
    private $promoOfferRenderList;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param RuleManagementInterface $ruleManagement
     * @param CheckoutSession $checkoutSession
     * @param PromoOfferRenderListInterface $promoOfferRenderList
     * @param Hydrator $hydrator
     */
    public function __construct(
        RuleManagementInterface $ruleManagement,
        CheckoutSession $checkoutSession,
        PromoOfferRenderListInterface $promoOfferRenderList,
        Hydrator $hydrator
    ) {
        $this->ruleManagement = $ruleManagement;
        $this->checkoutSession = $checkoutSession;
        $this->promoOfferRenderList = $promoOfferRenderList;
        $this->hydrator = $hydrator;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        $quote = $this->checkoutSession->getQuote();

        $metadataRules = $this->ruleManagement->getPopUpMetadataRules($quote->getId());
        $promoOffer = $this->promoOfferRenderList->getList($metadataRules, $quote->getStore()->getStoreId());

        return $this->hydrator->extract($promoOffer);
    }
}

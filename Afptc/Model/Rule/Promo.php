<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Model\Rule;

use Aheadworks\Afptc\Api\Data\PromoInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Promo
 *
 * @package Aheadworks\Afptc\Model\Rule
 */
class Promo extends AbstractExtensibleObject implements PromoInterface
{
    /**
     * {@inheritdoc}
     */
    public function setPromoOfferInfoText($promoOfferInfoText)
    {
        return $this->setData(self::PROMO_OFFER_INFO_TEXT, $promoOfferInfoText);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOfferInfoText()
    {
        return $this->_get(self::PROMO_OFFER_INFO_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelId($promoOnSaleLabelId)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_ID, $promoOnSaleLabelId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelId()
    {
        return $this->_get(self::PROMO_ON_SALE_LABEL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelLarge($promoOnSaleLabelLarge)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_LARGE, $promoOnSaleLabelLarge);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelLarge()
    {
        return $this->_get(self::PROMO_ON_SALE_LABEL_TEXT_LARGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelMedium($promoOnSaleLabelMedium)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_MEDIUM, $promoOnSaleLabelMedium);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelMedium()
    {
        return $this->_get(self::PROMO_ON_SALE_LABEL_TEXT_MEDIUM);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoOnSaleLabelSmall($promoOnSaleLabelSmall)
    {
        return $this->setData(self::PROMO_ON_SALE_LABEL_TEXT_SMALL, $promoOnSaleLabelSmall);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoOnSaleLabelSmall()
    {
        return $this->_get(self::PROMO_ON_SALE_LABEL_TEXT_SMALL);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoImage()
    {
        return $this->_get(self::PROMO_IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoImage($promoImage)
    {
        return $this->setData(self::PROMO_IMAGE, $promoImage);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoImageAltText()
    {
        return $this->_get(self::PROMO_IMAGE_ALT_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoImageAltText($promoImageAltText)
    {
        return $this->setData(self::PROMO_IMAGE_ALT_TEXT, $promoImageAltText);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoHeader()
    {
        return $this->_get(self::PROMO_HEADER);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoHeader($promoHeader)
    {
        return $this->setData(self::PROMO_HEADER, $promoHeader);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoDescription()
    {
        return $this->_get(self::PROMO_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoDescription($promoDescription)
    {
        return $this->setData(self::PROMO_DESCRIPTION, $promoDescription);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoUrl()
    {
        return $this->_get(self::PROMO_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoUrl($promoUrl)
    {
        return $this->setData(self::PROMO_URL, $promoUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoUrlText()
    {
        return $this->_get(self::PROMO_URL_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPromoUrlText($promoUrlText)
    {
        return $this->setData(self::PROMO_URL_TEXT, $promoUrlText);
    }
}

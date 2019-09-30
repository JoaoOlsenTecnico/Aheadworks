<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface PromoInterface
 * @api
 */
interface PromoInterface extends ExtensibleDataInterface
{
    const PROMO_OFFER_INFO_TEXT = 'promo_offer_info_text';
    const PROMO_ON_SALE_LABEL_ID = 'promo_on_sale_label_id';
    const PROMO_ON_SALE_LABEL_TEXT_LARGE = 'promo_on_sale_label_large';
    const PROMO_ON_SALE_LABEL_TEXT_MEDIUM = 'promo_on_sale_label_medium';
    const PROMO_ON_SALE_LABEL_TEXT_SMALL = 'promo_on_sale_label_small';
    const PROMO_IMAGE = 'promo_image';
    const PROMO_IMAGE_ALT_TEXT = 'promo_image_alt_text';
    const PROMO_HEADER = 'promo_header';
    const PROMO_DESCRIPTION = 'promo_description';
    const PROMO_URL = 'promo_url';
    const PROMO_URL_TEXT = 'promo_url_text';
    /**#@-*/

    /**
     * Get promo offer info text
     *
     * @return string
     */
    public function getPromoOfferInfoText();

    /**
     * Set promo offer info text
     *
     * @param string $promoOfferInfoText
     * @return $this
     */
    public function setPromoOfferInfoText($promoOfferInfoText);

    /**
     * Get promo onSale label ID
     *
     * @return int
     */
    public function getPromoOnSaleLabelId();

    /**
     * Set promo onSale label ID
     *
     * @param int $promoOnSaleLabelId
     * @return $this
     */
    public function setPromoOnSaleLabelId($promoOnSaleLabelId);

    /**
     * Get promo onSale label text large
     *
     * @return string
     */
    public function getPromoOnSaleLabelLarge();

    /**
     * Set promo onSale label text large
     *
     * @param string $promoOnSaleLabelLarge
     * @return $this
     */
    public function setPromoOnSaleLabelLarge($promoOnSaleLabelLarge);

    /**
     * Get promo onSale label text medium
     *
     * @return string
     */
    public function getPromoOnSaleLabelMedium();

    /**
     * Set promo onSale label text medium
     *
     * @param string $promoOnSaleLabelMedium
     * @return $this
     */
    public function setPromoOnSaleLabelMedium($promoOnSaleLabelMedium);

    /**
     * Get promo onSale label text small
     *
     * @return string
     */
    public function getPromoOnSaleLabelSmall();

    /**
     * Set promo onSale label text small
     *
     * @param string $promoOnSaleLabelSmall
     * @return $this
     */
    public function setPromoOnSaleLabelSmall($promoOnSaleLabelSmall);

    /**
     * Get promo image
     *
     * @return string
     */
    public function getPromoImage();

    /**
     * Set promo image
     *
     * @param string $promoImage
     * @return $this
     */
    public function setPromoImage($promoImage);

    /**
     * Get image alt text
     *
     * @return string
     */
    public function getPromoImageAltText();

    /**
     * Set promo image alt text
     *
     * @param string $promoImageAltText
     * @return $this
     */
    public function setPromoImageAltText($promoImageAltText);

    /**
     * Get promo header
     *
     * @return string
     */
    public function getPromoHeader();

    /**
     * Set promo header
     *
     * @param string $promoHeader
     * @return $this
     */
    public function setPromoHeader($promoHeader);

    /**
     * Get promo description
     *
     * @return string
     */
    public function getPromoDescription();

    /**
     * Set promo description
     *
     * @param string $promoDescription
     * @return $this
     */
    public function setPromoDescription($promoDescription);

    /**
     * Get promo url
     *
     * @return string
     */
    public function getPromoUrl();

    /**
     * Set promo url
     *
     * @param string $promoUrl
     * @return $this
     */
    public function setPromoUrl($promoUrl);

    /**
     * Get promo url text
     *
     * @return string
     */
    public function getPromoUrlText();

    /**
     * Set promo url text
     *
     * @param string $promoUrlText
     * @return $this
     */
    public function setPromoUrlText($promoUrlText);
}

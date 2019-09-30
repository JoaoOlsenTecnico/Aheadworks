<?php
/**
 * Copyright 2019 aheadWorks. All rights reserved.
See LICENSE.txt for license details.
 */

namespace Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor;

use Aheadworks\Afptc\Model\Rule\Image\Manager as ImageManager;
use Aheadworks\Afptc\Api\Data\RuleInterface;

/**
 * Class Image
 *
 * @package Aheadworks\Afptc\Ui\DataProvider\Rule\FormDataProcessor
 */
class Image implements ProcessorInterface
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @param ImageManager $imageManager
     */
    public function __construct(
        ImageManager $imageManager
    ) {
        $this->imageManager = $imageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareEntityData($data)
    {
        if (isset($data[RuleInterface::PROMO_IMAGE])) {
            if ($imageFileName = $data[RuleInterface::PROMO_IMAGE]) {
                try {
                    $url = $this->imageManager->getUrl($imageFileName);
                    $alt = isset($data[RuleInterface::PROMO_IMAGE_ALT_TEXT])
                        ? $data[RuleInterface::PROMO_IMAGE_ALT_TEXT]
                        : '';
                    $data[RuleInterface::PROMO_IMAGE] = [
                        0 => [
                            'file' => $imageFileName,
                            'url'  => $url,
                            'type' => 'image',
                            'alt'  => $alt
                        ]
                    ];
                } catch (\Exception $exception) {
                }
            }
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMetaData($meta)
    {
        return $meta;
    }
}

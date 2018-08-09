<?php

namespace SamJ\Instagram\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use SamJ\Instagram\Model\Base as InstagramBase;
use SamJ\Instagram\Model\Caption;

class Post extends InstagramBase
{
    const VALID_RESOLUTIONS = ['standard_resolution', 'low_resolution', 'thumbnail'];

    protected $caption_model;

    public function __construct(
        Caption $caption_model,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ){
        $this->caption_model = $caption_model;
        parent::__construct($scopeConfig, $context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Get the Image Object in the specified resolution
     *
     * @param string $resolution
     * @return mixed
     * @throws \Exception
     */
    public function getImage($resolution = 'standard_resolution')
    {
        if(!in_array($resolution, self::VALID_RESOLUTIONS, true)){
            throw new \Exception('Invalid resolution');
        }
        return $this->getImages()[$resolution];
    }


    /**
     * Return the URL of the image in the specified resolution
     *
     * @param string $resolution
     * @return mixed
     * @throws \Exception
     */
    public function getImageUrl($resolution = 'standard_resolution')
    {
        if(!in_array($resolution, self::VALID_RESOLUTIONS, true)){
            throw new \Exception('Invalid Resolution');
        }
        return $this->getImage($resolution)['url'];
    }


    /**
     * Alias for 'getLink()'
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->getLink();
    }


    /**
     * Get Likes Count For The Post
     *
     * @return mixed
     */
    public function getLikesCount()
    {
        return $this->getLikes()->count;
    }


    public function getCaption()
    {
        if($this->caption_model->getData() === null){
            $this->caption_model->setData($this->getData('caption'));
        }
        return $this->caption_model;
    }

    public function getCaptionText()
    {
        return $this->getCaption()->getText();
    }
}
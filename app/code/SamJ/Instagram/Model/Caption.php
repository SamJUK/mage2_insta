<?php

namespace SamJ\Instagram\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use SamJ\Instagram\Model\Base as InstagramBase;

class Caption extends InstagramBase
{

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ){
        parent::__construct($scopeConfig, $context, $registry, $resource, $resourceCollection, $data);
    }


}
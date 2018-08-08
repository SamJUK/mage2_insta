<?php

namespace SamJ\Instagram\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Base extends AbstractModel
{

    // No endpoint to check validity of access token, see so use this
    // and check if token works
    const API_TOKEN_TEST = 'https://api.instagram.com/v1/users/self/';

    const XML_PATH_ACCESS_TOKEN = 'samj_instagram/credentials/access_token';

    const ERROR_INVALID_TOKEN = 'OAuthAccessTokenException';

    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ){
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    public function getAccessToken()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ACCESS_TOKEN,
            ScopeInterface::SCOPE_STORE
        );
    }



    public function isAccessTokenValid()
    {
        $url = self::API_TOKEN_TEST . '?access_token=' . $this->getAccessToken();
        $res = $this->cURL($url, false);
        $json = json_decode($res);
        $is_response_valid = isset($json->meta->code) && $json->meta->code === 200;
        return $is_response_valid;
    }


    protected function cURL($url, $is_post = 0, $param = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if($is_post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
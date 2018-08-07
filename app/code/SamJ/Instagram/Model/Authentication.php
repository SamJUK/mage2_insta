<?php

namespace SamJ\Instagram\Model;

use SamJ\Instagram\Model\Base as InstagramBase;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Customer\Model\Visitor;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Url;
use Magento\Store\Model\ScopeInterface;

class Authentication extends InstagramBase
{

    const API_AUTH_URL = 'https://api.instagram.com/oauth/authorize/';
    const API_TOKEN_URL = 'https://api.instagram.com/oauth/access_token/';

    const XML_PATH_CLIENT_ID = 'samj_instagram/credentials/client_id';
    const XML_PATH_CLIENT_SECRET = 'samj_instagram/credentials/client_secret';


    /** @var ScopeConfigInterface  */
    protected $scopeConfig;

    /** @var Config  */
    protected $resourceConfig;

    /** @var Url  */
    protected $urlHelper;


    /**
     * Instagram constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $resourceConfig
     * @param Url $urlHelper
     * @param Context $context
     * @param Visitor $customerVisitor
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $resourceConfig,
        Url $urlHelper,

        Context $context,
        Visitor $customerVisitor,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ){
        $this->scopeConfig = $scopeConfig;
        $this->urlHelper = $urlHelper;
        $this->resourceConfig = $resourceConfig;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    /**
     * Build the URL to redirect to the instagram's authorization flow
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return self::API_AUTH_URL . '?' . http_build_query($this->getAuthParameters());
    }


    /**
     * Array of the parameters required for the initial auth flow url
     *
     * @return array
     */
    public function getAuthParameters()
    {
        return array(
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUri(),
            'response_type' => 'code'
        );
    }


    /**
     * Get the Client Id From system config
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLIENT_ID,
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Get the Client's Secret from the system config
     *
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLIENT_SECRET,
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Fetch the authorization frontend redirect URI
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->urlHelper->getUrl(
            'samj_instagram/authorize/response',
            array( '_nosid' => true )
        );
    }


    /**
     * Fetch a access token from the Instagram API using the set code
     *
     * @param $code
     * @return null
     */
    public function fetchAccessToken($code)
    {
        $res = $this->cURL( self::API_TOKEN_URL, 1, $this->getAccessTokenParams($code));
        $result = json_decode($res);

        return ($result && isset($result->access_token))
            ? $result->access_token
            : null;
    }


    /**
     * Array of the parameters to fetch a access token
     *
     * @param $code
     * @return array
     */
    public function getAccessTokenParams($code)
    {
        return array(
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
            'code' => $code
        );
    }


    /**
     * Update the access token in the system config
     *
     * @param $token
     * @return $this
     */
    public function updateAccessToken($token)
    {
        return $this->resourceConfig->saveConfig( self::XML_PATH_ACCESS_TOKEN, $token );
    }

}
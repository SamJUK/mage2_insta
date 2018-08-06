<?php

namespace SamJ\Instagram\Controller\Authorize;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use SamJ\Instagram\Model\Instagram;

class Response extends Action
{
    /** @var Http */
    private $request;

    /** @var Instagram */
    private $instagram;


    /**
     * Response constructor.
     * @param Instagram $instagram
     * @param Context $context
     * @param Http $request
     */
    public function __construct( Instagram $instagram, Context $context, Http $request)
    {
        $this->instagram = $instagram;
        $this->request = $request;
        parent::__construct($context);
    }


    /**
     * Main Function
     */
    public function execute()
    {
        $error = $this->request->getParam('error');
        $error_res = $this->request->getParam('error_reason');
        $error_desc = $this->request->getParam('error_description');

        if($error){
            echo 'The following error occurred with the authorization';
            echo 'Reason: ' . ($error_res ?: '');
            echo 'Description: ' . ($error_desc ?: '');
            die;
        }

        // We assume success at this point, so lets get the access token
        $code = $this->request->getParam('code');
        $access_token = $this->instagram->fetchAccessToken($code);
        $this->instagram->updateAccessToken($access_token);
        echo 'API Key Updated';

        // @TODO: Handle an automatic redirect back to admin panel
    }
}

<?php

namespace SamJ\Instagram\Model\ResourceModel;

//use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;

use SamJ\Instagram\Model\Base;

class User
{
    const API_ENDPOINT_SELF = 'https://api.instagram.com/v1/users/self/';

    protected $curl;
    protected $instagram;

    protected function __construct( Curl $curl, Base $instagram)
    {
        $this->curl = $curl;
        $this->instagram = $instagram;
    }

    protected function _construct()
    {
    }

    // Override CRUD methods
    // Save/Load/Delete ?
    public function save(){}
    public function load(AbstractModel $object, $value, $field = null)
    {
        $object->beforeLoad($value, $field);

        $url = SELF::API_ENDPOINT_SELF . '?access_token=' . $this->instagram->getAccessToken();
        $this->curl->write(\Zend_Http_Client::GET, $url);
        $res = $this->curl->read();

        echo '<pre>';
        var_dump($res);
        echo '</pre>';
        die;
    }
    public function delete(){}
}
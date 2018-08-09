<?php

namespace SamJ\Instagram\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use SamJ\Instagram\Model\Post;

use SamJ\Instagram\Model\Base;

class Posts extends AbstractDb
{
    protected $_idFieldName = 'id';
    const API_ENDPOINT_SELF = 'https://api.instagram.com/v1/users/self/media/recent/';

    protected $curl;
    protected $instagram;

    protected $post_model;

    protected function _construct() { }

    public function __construct( Curl $curl, Base $instagram, Post $post_model)
    {
        $this->curl = $curl;
        $this->instagram = $instagram;
        $this->post_model = $post_model;
    }

    // Override CRUD methods
    // Save/Load/Delete ?
    public function load(AbstractModel $object, $value, $field = null)
    {
        $object->beforeLoad($value, $field);

        $url = self::API_ENDPOINT_SELF . '?access_token=' . $this->instagram->getAccessToken();
        $this->curl->setConfig(['header' => false]);
        $this->curl->write(\Zend_Http_Client::GET, $url);


        // @TODO: VALIDATE REPONSE AND STUFF
        $data = json_decode($this->curl->read(), true)['data'];
        $array = array();


        // @TODO: Better way to do this?
        foreach ($data as $item){
            $post = clone $this->post_model;
            $post->setData($item);
            $array[] = $post;
        }


        if($data){
            $object->setData($array);
        }


        $this->unserializeFields($object);
        $this->_afterLoad($object);
        $object->afterLoad();
        $object->setOrigData();
        $object->setHasDataChanges(false);

        return $this;
    }

    public function save(AbstractModel $object){}
    public function delete(AbstractModel $object){}
}
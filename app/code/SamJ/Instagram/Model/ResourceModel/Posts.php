<?php

namespace SamJ\Instagram\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Psr\Log\LoggerInterface;
use SamJ\Instagram\Model\Cache\Type;
use SamJ\Instagram\Model\Post;

use SamJ\Instagram\Model\Base;

class Posts extends AbstractDb
{
    const API_ENDPOINT_SELF = 'https://api.instagram.com/v1/users/self/media/recent/';
    const CACHE_IDENTIFIER = 'posts';

    protected $_idFieldName = 'id';

    protected $curl;
    protected $instagram;
    protected $post_model;
    protected $_cacheType;
    protected $logger;

    protected function _construct() { }

    public function __construct( Curl $curl, Base $instagram, Post $post_model, Type $cacheType, LoggerInterface $logger)
    {
        $this->curl = $curl;
        $this->instagram = $instagram;
        $this->post_model = $post_model;
        $this->_cacheType = $cacheType;
        $this->logger = $logger;
    }

    // Override CRUD methods
    // Save/Load/Delete ?
    public function load(AbstractModel $object, $value, $field = null)
    {
        $object->beforeLoad($value, $field);

        $cached = $this->_cacheType->load(self::CACHE_IDENTIFIER);

//        var_dump($cached);

        if($cached){
            $this->logger->info('SamJ Posts: Used Cache');
            $data = unserialize($cached);
        }else{
            // @TODO: Abstract this into a fetch method for the external part
            $this->logger->info('SamJ Posts: Fetched From API');
            $url = self::API_ENDPOINT_SELF . '?access_token=' . $this->instagram->getAccessToken();
            $this->curl->setConfig(['header' => false]);
            $this->curl->write(\Zend_Http_Client::GET, $url);


            // @TODO: VALIDATE REPONSE AND STUFF
            $data = json_decode($this->curl->read(), true)['data'];

            $this->_cacheType->save(
                serialize($data),
                self::CACHE_IDENTIFIER,
                [\SamJ\Instagram\Model\Cache\Type::CACHE_TAG],
                $this->_cacheType->getCacheLifeTime()
            );
        }


        // @TODO: Better way to do this?
        $array = array();
        foreach ($data as $item){
            $post = clone $this->post_model;
            $post->setData($item);
            $array[] = $post;
        }


        if($array){
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
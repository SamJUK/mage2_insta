<?php

namespace SamJ\Instagram\Block;

use Magento\Backend\Block\Template;

class Posts extends Template
{

    protected $posts;

    public function __construct(
        \SamJ\Instagram\Model\Post $post,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ){
        $this->posts = $post->load('self');
        parent::__construct($context, $data);
    }


    public function getFeed()
    {
        return $this->posts->getData();
    }

}
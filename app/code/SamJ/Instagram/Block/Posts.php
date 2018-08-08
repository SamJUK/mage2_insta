<?php

namespace SamJ\Instagram\Block;

use Magento\Backend\Block\Template;

class Posts extends Template
{

    public function __construct(
        \SamJ\Instagram\Model\Post $post,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
    }

}
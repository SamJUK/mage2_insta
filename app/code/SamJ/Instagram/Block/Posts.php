<?php

namespace SamJ\Instagram\Block;

use Magento\Backend\Block\Template;

class Posts extends Template
{

    public function __construct(\SamJ\Instagram\Model\User $user)
    {
        $user->load(123);
    }

}
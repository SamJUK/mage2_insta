<?php

namespace SamJ\Instagram\Model;

use SamJ\Instagram\Model\Base as InstagramBase;

class User extends InstagramBase
{

    public function _construct()
    {
        $this->_init(\SamJ\Instagram\Model\ResourceModel\User::class);
    }

}
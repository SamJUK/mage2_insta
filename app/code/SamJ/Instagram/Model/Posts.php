<?php

namespace SamJ\Instagram\Model;

use \Magento\Framework\Model\AbstractModel;

class Posts extends AbstractModel
{


    public function getFeed($user, $count = 5)
    {

    }

    public function getOwnFeed($count = 5)
    {
        return $this->getFeed('self', $count);
    }


}
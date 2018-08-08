<?php

namespace SamJ\Instagram\Block;

use Magento\Backend\Block\Template;

class Users extends Template
{
    protected $user;

    public function __construct(
        \SamJ\Instagram\Model\User $user,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->user = $user->load('self');
        parent::__construct($context, $data);
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

}
<?php

class GuestbookController extends Zend_Controller_Action
{
    protected $guestbook;

    public function init()
    {
        $this->guestbook = new Application_Model_GuestbookMapper();
    }

    public function indexAction()
    {
        $this->view->entries = $this->guestbook->fetchAll();
    }
}
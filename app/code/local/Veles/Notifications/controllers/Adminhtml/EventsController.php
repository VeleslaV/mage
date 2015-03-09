<?php
    class Veles_Notifications_Adminhtml_EventsController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('veles_notifications');
            $this->_addContent($this->getLayout()->createBlock('veles_notifications/adminhtml_events'));
            $this->renderLayout();
        }
    }
<?php
    class Veles_Notifications_TestController extends Mage_Core_Controller_Front_Action
    {
        public function testAction()
        {
            $ruleModel = Mage::getModel('veles_notifications/queue');
            $ruleModel->sendNotification();
        }
    }
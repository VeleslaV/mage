<?php
    class Veles_Notifications_TestController extends Mage_Core_Controller_Front_Action
    {
        /**
         * url to call this action http://[path_to_mage_dir]/notifications/test/test/qid/[int]
         * where [int] its queue id
         */
        public function testAction()
        {
            $qid = Mage::app()->getRequest()->getParam('qid');

            /** @var Veles_Notifications_Model_Queue $queueModel */
            $queueModel = Mage::getModel('veles_notifications/queue');
            $sendResult = $queueModel->sendNotificationTest($qid);

            if($sendResult->getData('queue_id')){
                echo "
                    send Queue Id: ".$sendResult->getData('queue_id')."<br>
                    set Queue Status: ".$sendResult->getData('queue_status')."<br>
                ";
            }else{
                echo "
                  nothing to send
                ";
            }
        }
    }
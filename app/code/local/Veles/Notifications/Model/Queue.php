<?php
    class Veles_Notifications_Model_Queue extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/queue');
        }

        public function createNewQueueItem($rule, $data, $type)
        {
            $dateTime = now();
            $queueData = array(
                'rule_id' => $rule->getData('rule_id'),
                'quote_or_order' => $type,
                'customer_name' => $data->getData('customer_firstname')." ".$data->getData('customer_lastname'),
                'customer_email' => $data->getData('customer_email'),
                'created_at' => strtotime($dateTime),
                'scheduled_at' => strtotime($dateTime . " +30 days")
            );

            if($type == "quote"){
                //check if quote already exist in table, it means that customer add new product to the cart. Update existing line, instead of creating a new.
                $queueCollection = $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('main_table.quote_id', array('eq'=>$data->getData('entity_id')));
                $queueCollection->getSelect()->limit(1);
                $queueId = $queueCollection->getFirstItem()->getData('queue_id'); //get queue_id to update existing queue item

                $queueData['quote_id'] = $data->getData('entity_id');
                $queueData['order_id'] = "0";
            }else{
                $queueId = null; //set queue_id null to create new line in queue table

                $queueData['order_id'] = $data->getData('entity_id');
                $queueData['quote_id'] = $data->getData('quote_id');
            }

            $this->setData($queueData)->setId($queueId);

            try {
                $insertId = $this->save()->getId();
                echo "Data successfully inserted. Insert ID: ".$insertId;
            } catch (Exception $e){
                echo $e->getMessage();
            }

            return $this;
        }

        public function sendNotification()
        {
            $helper = Mage::helper('veles_notifications');

            $dateTime = now();
            $queueCollection = $this->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.queue_status', array('eq'=>'0'))
                ->addFieldToFilter('main_table.scheduled_at', array('to' => $dateTime));
            $queueCollection->getSelect();

            if(sizeof($queueCollection)>0){
                foreach($queueCollection as $key => $item) {
                    try {
                        $helper->sendNotificationEmail($item);

                        // update queue item status 1 = Success
                        $queueData = array('queue_status' => '1');
                        $this->addData($queueData)->setId($item->getId());

                        $logString = "sendNotificationEmail Success, ,".date("M j Y G:i:s");
                        echo "ok";
                    } catch (Exception $e){
                        echo $e->getMessage();

                        // update queue item status 2 = Fail
                        $queueData = array('queue_status' => '2');
                        $this->addData($queueData)->setId($item->getId());

                        $logString = "sendNotificationEmail Error, ".$e->getMessage().", ".date("M j Y G:i:s");
                    }

                    $this->save();
                    $helper->createLog($logString);
                }
            }else{
                $logString = "sendNotificationEmail , There no any notifications to send, ".date("M j Y G:i:s");
                $helper->createLog($logString);
            }

            return $this;
        }
    }
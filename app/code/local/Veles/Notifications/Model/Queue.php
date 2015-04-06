<?php

    /**
     * Class Veles_Notifications_Model_Queue
     */
    class Veles_Notifications_Model_Queue extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/queue');
        }



        /**
         * @param $rule
         * @param $data
         * @param array $params
         * @return $this
         */
        public function createNewQueueItem($rule, $data, array $params)
        {
            $type = $params['type'];
            $coupon = $params['coupon'];
            $product = $params['product'];

            $dateTime = now();
            $queueData = array(
                'rule_id' => $rule->getData('rule_id'),
                'quote_or_order' => $type,
                'customer_name' => $data->getData('customer_firstname')." ".$data->getData('customer_lastname'),
                'customer_email' => $data->getData('customer_email'),
                'coupon' => $coupon,
                'product' => $product,
                'created_at' => strtotime($dateTime),
                'scheduled_at' => strtotime($dateTime . " +".$rule->getData('send_at'))
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



        /**
         * @param $itemId
         * @param $itemType
         * @return mixed
         */
        public function getQueueItem($itemId, $itemType)
        {
            if($itemType == "order"){
                $queueCollection = $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('main_table.queue_status', array('eq'=>'0'))
                    ->addFieldToFilter('main_table.quote_or_order', array('eq'=>'order'))
                    ->addFieldToFilter('main_table.order_id', array('eq'=>$itemId));
            }else{
                $queueCollection = $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('main_table.queue_status', array('eq'=>'0'))
                    ->addFieldToFilter('main_table.quote_or_order', array('eq'=>'quote'))
                    ->addFieldToFilter('main_table.quote_id', array('eq'=>$itemId));
            }

            $queueCollection->getSelect()->limit(1);
            $queueItem = $queueCollection->getFirstItem();

            return $queueItem;
        }



        /**
         * @param $itemId
         * @return $this
         * @throws Exception
         */
        public function cancelQueueItem($itemId)
        {
            // update queue item status 3 = Canceled
            $queueData = array('queue_status' => '3');
            $this->addData($queueData)->setId($itemId);
            $this->save();

            return $this;
        }



        /**
         * @param $itemId
         * @return $this
         * @throws Exception
         */
        public function removeQueueItem($itemId)
        {
            $this->setId($itemId)->delete();

            return $this;
        }



        /**
         * @return string
         */
        public function generateCouponCode()
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');
            $shoppingCartPriceRuleId = $helper->getShoppingCartPriceRuleIdFromModuleOptions();

            if($shoppingCartPriceRuleId == ""){
                $couponCode = null;
            }else{
                // Get the Shopping Cart Price Rule
                $cartPriceRule = Mage::getModel('salesrule/rule')->load($shoppingCartPriceRuleId);

                // Define a coupon code generator model instance
                // Look at Mage_SalesRule_Model_Coupon_Massgenerator for options
                $generator = Mage::getModel('salesrule/coupon_massgenerator');

                $parameters = array(
                    'count'=>1,
                    'format'=>'alphanumeric',
                    'dash_every_x_characters'=>4,
                    'prefix'=>'ABCD-',
                    'suffix'=>'-WXYZ',
                    'length'=>8
                );

                if( !empty($parameters['format']) ){
                    switch( strtolower($parameters['format']) ){
                        case 'alphanumeric':
                        case 'alphanum':
                            $generator->setFormat( Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHANUMERIC );
                            break;
                        case 'alphabetical':
                        case 'alpha':
                            $generator->setFormat( Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_ALPHABETICAL );
                            break;
                        case 'numeric':
                        case 'num':
                            $generator->setFormat( Mage_SalesRule_Helper_Coupon::COUPON_FORMAT_NUMERIC );
                            break;
                    }
                }

                $generator->setDash( !empty($parameters['dash_every_x_characters'])? (int) $parameters['dash_every_x_characters'] : 0);
                $generator->setLength( !empty($parameters['length'])? (int) $parameters['length'] : 6);
                $generator->setPrefix( !empty($parameters['prefix'])? $parameters['prefix'] : '');
                $generator->setSuffix( !empty($parameters['suffix'])? $parameters['suffix'] : '');

                // Set the generator, and coupon type so it's able to generate
                $cartPriceRule->setCouponCodeGenerator($generator);
                $cartPriceRule->setCouponType( Mage_SalesRule_Model_Rule::COUPON_TYPE_AUTO );

                // Create coupon code
                $coupon = $cartPriceRule->acquireCoupon();
                $coupon->setType(Mage_SalesRule_Helper_Coupon::COUPON_TYPE_SPECIFIC_AUTOGENERATED)->save();

                $couponCode = $coupon->getCode();
            }

            return $couponCode;
        }



        /**
         * @return Veles_Notifications_Model_Queue
         */
        public function sendNotificationCron()
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');
            $helper->createLog(", sendNotificationCron, Start, Looking for available IDs, ".now());

            $queueCollection = $this->getCollection();
            $queueCollection->addFieldToSelect('*');
            $queueCollection->addFieldToFilter('main_table.queue_status', array('eq'=>"0"));
            $queueCollection->addFieldToFilter('main_table.scheduled_at', array('to' => now()));
            $queueCollection->getSelect();

            $sendingResult = $this->_processSendingNotification($queueCollection);

            return $sendingResult;
        }



        /**
         * @param int $queueId
         * @return Veles_Notifications_Model_Queue
         */
        public function sendNotificationTest($queueId = 0)
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');
            $helper->createLog(", sendNotificationTest, Start, Get specific ID $queueId, ".now());

            $queueCollection = $this->getCollection();
            $queueCollection->addFieldToSelect('*');
            $queueCollection->addFieldToFilter('main_table.queue_status', array('eq'=>"0"));
            $queueCollection->addFieldToFilter('main_table.queue_id', array('eq'=>"$queueId"));
            $queueCollection->getSelect();

            $sendingResult = $this->_processSendingNotification($queueCollection);

            return $sendingResult;
        }



        /**
         * @param $queueCollection
         * @return $this
         * @throws Exception
         */
        private function _processSendingNotification($queueCollection)
        {
            $callers = debug_backtrace();
            $caller = $callers[1]['function'];

            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');

            if(sizeof($queueCollection->getData())>0){
                foreach($queueCollection as $key => $item) {

                    try {
                        $helper->sendNotificationEmail($item);

                        // update queue item status 1 = Success
                        $queueData = array('queue_status' => '1');
                        $this->addData($queueData)->setId($item->getId());

                        $logString = ", $caller, Success, queueId = ".$item->getId().", ".now();
                    } catch (Exception $e){
                        // update queue item status 2 = Fail
                        $queueData = array('queue_status' => '2');
                        $this->addData($queueData)->setId($item->getId());

                        $logString = ", $caller, Error, ".$e->getMessage().", ".now();
                    }

                    $this->save();
                    $helper->createLog($logString);
                }
            }else{
                $logString = ", $caller, Null, No any notifications to send, ".now();
                $helper->createLog($logString);
            }

            return $this;
        }
    }
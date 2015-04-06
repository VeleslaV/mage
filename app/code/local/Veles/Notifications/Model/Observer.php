<?php
    class Veles_Notifications_Model_Observer
    {
        /** @var Veles_Notifications_Model_Event $_eventModel */
        protected $_eventModel;

        /** @var Veles_Notifications_Model_Rule $_ruleModel */
        protected $_ruleModel;

        /** @var Veles_Notifications_Model_Queue $_queueModel */
        protected $_queueModel;

        /** @var Veles_Notifications_Model_UserProduct $_userProductModel */
        protected $_userProductModel;



        /**
         * @param Varien_Event_Observer $observer
         * @return $this
         */
        public function checkoutCartAddProductComplete(Varien_Event_Observer $observer)
        {
            $this->_eventModel = Mage::getModel('veles_notifications/event');
            $this->_ruleModel = Mage::getModel('veles_notifications/rule');
            $this->_queueModel = Mage::getModel('veles_notifications/queue');
            $eId = $this->_eventModel->getEventIdByName($observer->getEvent()->getName()); // eventId

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Cancel Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToCancel */
            $ruleToCancel = $this->_ruleModel->getRuleByEventId($eId, "cancel"); //rule to cancel with current event
            if(sizeof($ruleToCancel->getData())>0) {
                /*
                 * in the future there will be
                 * $this->_queueModel->cancelQueueItem(),
                 * if such a need (it will be cancellation event for the some rule )
                 */
            }

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Start Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToStart */
            $ruleToStart = $this->_ruleModel->getRuleByEventId($eId, "start"); //rule to start with current event
            if(sizeof($ruleToStart->getData())>0) {
                /** @var Mage_Checkout_Model_Session $quoteModel */
                $quoteModel = Mage::getSingleton('checkout/session');
                $quoteModel->getQuote();
                $quoteId = $quoteModel->getQuoteId(); //get quoteId

                /** @var Mage_Sales_Model_Quote $quote */
                $quote = Mage::getModel('sales/quote');
                $quote->loadByIdWithoutStore($quoteId); //get quote details by quoteId

                $ruleCGId = $ruleToStart->getData('group_id'); // customerGroupId in rule
                $orderCGId = $quote->getData('customer_group_id'); // current user groupId
                $ruleStoreId = $ruleToStart->getData('store_id'); // storeId in rule
                $orderStoreId = $quote->getData('store_id'); // current storeId

                if(($ruleStoreId == "0" OR $ruleStoreId == $orderStoreId) AND ($ruleCGId == $orderCGId)){
                    $this->_queueModel->createNewQueueItem($ruleToStart, $quote, array('type' => 'quote'));
                }
            }

            return $this;
        }



        /**
         * @param Varien_Event_Observer $observer
         * @return $this
         */
        public function orderPlaceAfter(Varien_Event_Observer $observer)
        {
            $this->_eventModel = Mage::getModel('veles_notifications/event');
            $this->_ruleModel = Mage::getModel('veles_notifications/rule');
            $this->_queueModel = Mage::getModel('veles_notifications/queue');
            $eId = $this->_eventModel->getEventIdByName($observer->getEvent()->getName()); // eventId

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Cancel Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToCancel */
            $ruleToCancel = $this->_ruleModel->getRuleByEventId($eId, "cancel"); // rule to cancel with current event
            if(sizeof($ruleToCancel->getData())>0) {
                $order = $observer->getEvent()->getOrder();

                /** @var Veles_Notifications_Model_Queue $queueItem */
                $queueItem = $this->_queueModel->getQueueItem($order->getData('quote_id'), "quote"); //get queue item
                $queueId = $queueItem->getData('queue_id');

                if(!empty($queueId)){
                    $this->_queueModel->cancelQueueItem($queueId);
                }
            }

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Start Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToStart */
            $ruleToStart = $this->_ruleModel->getRuleByEventId($eId, "start"); // rule to start with current event
            if(sizeof($ruleToStart->getData())>0) {
                /*
                 * in the future there will be
                 * $this->_queueModel->createNewQueueItem(),
                 * if such a need (it will be start event for the some rule )
                 */
            }

            return $this;
        }



        /**
         * @param Varien_Event_Observer $observer
         * @return $this
         */
        public function salesOrderShipmentSaveAfter(Varien_Event_Observer $observer)
        {
            $this->_eventModel = Mage::getModel('veles_notifications/event');
            $this->_ruleModel = Mage::getModel('veles_notifications/rule');
            $this->_queueModel = Mage::getModel('veles_notifications/queue');
            $eId = $this->_eventModel->getEventIdByName($observer->getEvent()->getName()); // eventId

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Cancel Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToCancel */
            $ruleToCancel = $this->_ruleModel->getRuleByEventId($eId, "cancel"); //rule to cancel with current event
            if(sizeof($ruleToCancel->getData())>0) {
                /*
                 * in the future there will be
                 * $this->_queueModel->cancelQueueItem(),
                 * if such a need (it will be cancellation event for the some rule )
                 */
            }

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Start Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToStart */
            $ruleToStart = $this->_ruleModel->getRuleByEventId($eId, "start"); //get rule for current event
            if(sizeof($ruleToStart->getData())>0) {
                $order = $observer->getEvent()->getShipment()->getOrder(); //get order details

                $ruleCGId = $ruleToStart->getData('group_id');
                $orderCGId = $order->getData('customer_group_id');
                $ruleStoreId = $ruleToStart->getData('store_id');
                $orderStoreId = $order->getData('store_id');

                if(($ruleStoreId == "0" OR $ruleStoreId == $orderStoreId) AND ($ruleCGId == $orderCGId)){
                    //create new queue item
                    $this->_queueModel->createNewQueueItem($ruleToStart, $order, array('type' => 'order'));
                }
            }

            return $this;
        }



        /**
         * @param Varien_Event_Observer $observer
         * @return $this
         */
        public function invoiceSaveAfter(Varien_Event_Observer $observer)
        {
            $this->_eventModel = Mage::getModel('veles_notifications/event');
            $this->_ruleModel = Mage::getModel('veles_notifications/rule');
            $this->_queueModel = Mage::getModel('veles_notifications/queue');
            $this->_userProductModel = Mage::getModel('veles_notifications/userProduct');
            $eId = $this->_eventModel->getEventIdByName($observer->getEvent()->getName()); // eventId

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Cancel Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToCancel */
            $ruleToCancel = $this->_ruleModel->getRuleByEventId($eId, "cancel"); //rule to cancel with current event
            if(sizeof($ruleToCancel->getData())>0) {
                /*
                 * in the future there will be
                 * $this->_queueModel->cancelQueueItem(),
                 * if such a need (it will be cancellation event for the some rule )
                 */
            }

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Start Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToStart */
            $ruleToStart = $this->_ruleModel->getRuleByEventId($eId, "start"); //get rule for current event
            if(sizeof($ruleToStart->getData())>0) {
                $order = $observer->getEvent()->getInvoice()->getOrder();

                $ruleCGId = $ruleToStart->getData('group_id');
                $orderCGId = $order->getData('customer_group_id');
                $ruleStoreId = $ruleToStart->getData('store_id');
                $orderStoreId = $order->getData('store_id');

                if(($ruleStoreId == "0" OR $ruleStoreId == $orderStoreId) AND ($ruleCGId == $orderCGId)){
                    $items = $order->getAllVisibleItems();

                    //save bought products into the user-product table
                    foreach ($items as $itemId => $item) {
                        for($i=0; $i<$item->getData('qty_invoiced'); $i++){
                            $this->_userProductModel->saveData(
                                $order->getData('customer_id'),
                                $item->getData('product_id')
                            );
                        }
                    }

                    //die();
                    //then calculate how many times customer bought these products
                    foreach ($items as $itemId => $item) {
                        $boughtCount = $this->_userProductModel->calculateCountForProductId(
                            $order->getData('customer_id'),
                            $item->getData('product_id')
                        );

                        if($boughtCount >= $ruleToStart->getData('products_count')){
                            // generate coupon code
                            $couponCode = $this->_queueModel->generateCouponCode();

                            // create new queue item
                            $this->_queueModel->createNewQueueItem($ruleToStart, $order, array(
                                'type' => 'order',
                                'coupon' => $couponCode,
                                'product' => $item->getData('product_id')
                            ));

                            // and remove information about this products from user-products module table
                            $this->_userProductModel->removeThisItems(
                                $order->getData('customer_id'),
                                $item->getData('product_id')
                            );

                            break;
                        }
                    }
                }
            }

            return $this;
        }



        /**
         * @param Varien_Event_Observer $observer
         * @return $this
         */
        public function orderCancelAfter(Varien_Event_Observer $observer)
        {
            $this->_eventModel = Mage::getModel('veles_notifications/event');
            $this->_ruleModel = Mage::getModel('veles_notifications/rule');
            $this->_queueModel = Mage::getModel('veles_notifications/queue');
            $eId = $this->_eventModel->getEventIdByName($observer->getEvent()->getName()); // eventId

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Cancel Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToCancel */
            $ruleToCancel = $this->_ruleModel->getRuleByEventId($eId, "cancel"); //rule to cancel with current event
            if(sizeof($ruleToCancel->getData())>0) {
                $order = $observer->getEvent()->getOrder();

                $itemId = $order->getData('entity_id'); //get queue item
                $queueItem = $this->_queueModel->getQueueItem($itemId, "order");

                if(sizeof($queueItem->getData()>0)) {
                    $this->_queueModel->cancelQueueItem($queueItem->getData('queue_id'));
                }
            }

            /*<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Start Event Logic >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/
            /** @var Veles_Notifications_Model_Rule $ruleToStart */
            $ruleToStart = $this->_ruleModel->getRuleByEventId($eId, "start"); // rule to start with current event
            if(sizeof($ruleToStart->getData())>0) {
                /*
                 * in the future there will be
                 * $this->_queueModel->createNewQueueItem(),
                 * if such a need (it will be start event for the some rule )
                 */
            }

            return $this;
        }
    }
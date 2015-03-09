<?php
    class Veles_Notifications_Model_Observer
    {
        public function checkoutCartProductAddAfter(Varien_Event_Observer $observer)
        {
            $ruleModel = Mage::getModel('veles_notifications/rule');
            $eventModel = Mage::getModel('veles_notifications/event');
            $queueModel = Mage::getModel('veles_notifications/queue');

            $eventName = $observer->getEvent()->getName();
            $eventId = $eventModel->getEventIdByName($eventName);
            $quoteId = $observer->getEvent()->getQuoteItem()->getData('quote_id');

            $quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($quoteId);

            //get rule for current event
            $ruleCollection = $ruleModel->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.rule_status', array('eq'=>'1'))
                ->addFieldToFilter('main_table.event_id', array('eq'=>$eventId));
            $ruleCollection->getSelect()->limit(1);
            $rule = $ruleCollection->getFirstItem();

            if(sizeof($rule)>0) {
                $ruleCustomerGroupId = $rule->getData('group_id');
                $orderCustomerGroupId = $quote->getData('customer_group_id');

                $ruleStoreId = $rule->getData('store_id');
                $orderStoreId = $quote->getData('store_id');

                if(($ruleStoreId == "0" OR $ruleStoreId == $orderStoreId) AND ($ruleCustomerGroupId == $orderCustomerGroupId)){
                    //create new queue item
                    $queueModel->createNewQueueItem($rule, $quote, "quote");
                }
            }

            return $this;
        }

        public function salesOrderShipmentSaveAfter(Varien_Event_Observer $observer)
        {
            $ruleModel = Mage::getModel('veles_notifications/rule');
            $eventModel = Mage::getModel('veles_notifications/event');
            $queueModel = Mage::getModel('veles_notifications/queue');

            //get order details
            $shipment = $observer->getEvent()->getShipment();
            $eventName = $observer->getEvent()->getName();
            $order = $shipment->getOrder();

            $eventId = $eventModel->getEventIdByName($eventName);

            //get rule for current event
            $ruleCollection = $ruleModel->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.rule_status', array('eq'=>'1'))
                ->addFieldToFilter('main_table.event_id', array('eq'=>$eventId));
            $ruleCollection->getSelect()->limit(1);
            $rule = $ruleCollection->getFirstItem();

            if(sizeof($rule)>0) {
                $ruleCustomerGroupId = $rule->getData('group_id');
                $orderCustomerGroupId = $order->getData('customer_group_id');

                $ruleStoreId = $rule->getData('store_id');
                $orderStoreId = $order->getData('store_id');

                if(($ruleStoreId == "0" OR $ruleStoreId == $orderStoreId) AND ($ruleCustomerGroupId == $orderCustomerGroupId)){
                    //create new queue item
                    $queueModel->createNewQueueItem($rule, $order, "order");
                }
            }

            return $this;
        }
    }
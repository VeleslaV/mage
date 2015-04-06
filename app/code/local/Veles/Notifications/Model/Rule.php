<?php

/**
 * Class Veles_Notifications_Model_Rule
 */
class Veles_Notifications_Model_Rule extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/rule');
        }



        /**
         * @param $eventId
         * @param $eventType
         * @return mixed
         */
        public function getRuleByEventId($eventId, $eventType)
        {
            if($eventType == "cancel"){
                $ruleCollection = $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('main_table.rule_status', array('eq'=>'1'))
                    ->addFieldToFilter('main_table.cancel_event_id', array('eq'=>$eventId));
            }else{
                $ruleCollection = $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('main_table.rule_status', array('eq'=>'1'))
                    ->addFieldToFilter('main_table.event_id', array('eq'=>$eventId));
            }

            $ruleCollection->getSelect()->limit(1);
            $rule = $ruleCollection->getFirstItem();

            return $rule;
        }
    }
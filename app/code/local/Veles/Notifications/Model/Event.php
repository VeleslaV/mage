<?php

/**
 * Class Veles_Notifications_Model_Event
 */
class Veles_Notifications_Model_Event extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/event');
        }



        /**
         * @param $eventName
         * @return mixed
         */
        public function getEventIdByName($eventName)
        {
            $eventCollection = $this->getCollection();
            $eventCollection->addFieldToFilter('main_table.event_name', array('eq'=>$eventName));
            $eventCollection->getSelect()->limit(1);
            $eventId = $eventCollection->getFirstItem()->getData('event_id');

            return $eventId;
        }
    }
<?php
    class Veles_Notifications_Model_Resource_Event extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_notifications/veles_notifications_events_table', 'event_id');
        }

    }
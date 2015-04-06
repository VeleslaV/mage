<?php
    class Veles_Notifications_Model_Resource_Rule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/rule');
        }

    }
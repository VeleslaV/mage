<?php
    class Veles_Notifications_Model_Resource_Rule extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_notifications/veles_notifications_rules_table', 'rule_id');
        }

    }
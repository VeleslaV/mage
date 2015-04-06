<?php
    class Veles_Notifications_Model_Resource_UserProduct extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_notifications/veles_notifications_user_products_table', 'line_id');
        }
    }
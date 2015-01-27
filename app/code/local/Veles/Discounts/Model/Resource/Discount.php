<?php
    class Veles_Discounts_Model_Resource_Discount extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_discounts/veles_customers_discounts_table', 'customer_id');
            $this->_isPkAutoIncrement = false;
        }
    }
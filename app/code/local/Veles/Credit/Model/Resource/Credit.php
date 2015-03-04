<?php
    class Veles_Credit_Model_Resource_Credit extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('credit/table_credit', 'customer_id');
            $this->_isPkAutoIncrement = false;
        }
    }
<?php
    class Veles_Fee_Model_Resource_Fee extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('fee/table_fee', 'customer_id');
        }
    }
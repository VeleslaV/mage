<?php
    class Veles_Discounts_Model_Resource_Level extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_discounts/veles_discounts_levels_table', 'entity_id');
//            $this->_isPkAutoIncrement = false;
        }
    }
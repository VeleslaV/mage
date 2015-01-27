<?php
    class Veles_Discounts_Model_Resource_Level_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discounts/level');
        }
    }
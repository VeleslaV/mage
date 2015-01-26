<?php
    class Veles_Discount_Model_Resource_Discount_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discount/discount');
        }
    }
<?php
    class Veles_Discount_Model_Resource_Discount extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('veles_discount/veles_discount_table', 'entity_id');
        }
    }
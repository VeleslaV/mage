<?php
    class Veles_FAQ_Model_Resource_Cq_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('velesfaq/cq');
        }
    }
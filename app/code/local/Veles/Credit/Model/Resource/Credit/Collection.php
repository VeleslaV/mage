<?php
    class Veles_Credit_Model_Resource_Credit_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('credit/credit');
        }
    }
<?php
    class Veles_FAQ_Model_Cq extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('velesfaq/cq');
        }
    }
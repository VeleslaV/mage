<?php
    class Veles_Discounts_Model_Level extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discounts/level');
        }

    }
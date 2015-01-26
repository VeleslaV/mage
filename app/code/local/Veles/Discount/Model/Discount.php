<?php
    class Veles_Discount_Model_Discount extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discount/discount');
        }

    }
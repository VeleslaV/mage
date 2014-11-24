<?php
    class Veles_FAQ_Model_Faq extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('velesfaq/faq');
        }

        public function getCategoriesCollection()
        {
            $helper = Mage::helper('velesfaq');
            $collection = $helper->joinMyCategoriesCollection($this->getId());

            return $collection;
        }
    }
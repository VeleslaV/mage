<?php
    class Veles_News_Model_News extends Mage_Core_Model_Abstract
    {

        public function _construct()
        {
            parent::_construct();
            $this->_init('velesnews/news');
        }

        protected function _afterDelete()
        {
            $helper = Mage::helper('velesnews');
            @unlink($helper->getImagePath($this->getId()));
            return parent::_afterDelete();
        }

        public function getImageUrl(    )
        {
            $helper = Mage::helper('velesnews');
            if ($this->getId() && file_exists($helper->getImagePath($this->getId()))) {
                return $helper->getImageUrl($this->getId());
            }
            return null;
        }

    }
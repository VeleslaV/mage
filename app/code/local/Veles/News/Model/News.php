<?php
    class Veles_News_Model_News extends Mage_Core_Model_Abstract
    {

        public function _construct()
        {
            parent::_construct();
            $this->_init('velesnews/news');
        }

        protected function _beforeSave()
        {
            $helper = Mage::helper('velesnews');

            if (!$this->getData('link')) {
                $this->setData('link', $helper->prepareUrl($this->getTitle()));
            } else {
                $this->setData('link', $helper->prepareUrl($this->getData('link')));
            }
            return parent::_beforeSave();
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
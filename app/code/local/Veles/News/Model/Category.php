<?php
    class Veles_News_Model_Category extends Mage_Core_Model_Abstract
    {

        protected function _construct()
        {
            parent::_construct();
            $this->_init('velesnews/category');
        }

        protected function _afterDelete()
        {
            foreach($this->getNewsCollection() as $news){
                $news->setCategoryId(0)->save();
            }
            return parent::_afterDelete();
        }

        public function getNewsCollection()
        {
            $collection = Mage::getModel('velesnews/news')->getCollection();
            $collection->addFieldToFilter('category_id', $this->getId());
            return $collection;
        }
    }
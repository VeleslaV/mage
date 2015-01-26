<?php
    class Veles_FAQ_Model_Category extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('velesfaq/category');
        }

        protected function _afterDelete()
        {
            $questionsCollection = Mage::getModel('velesfaq/faq')->getCollection()
                ->addFieldToFilter('category_id', $this->getId());
            foreach($questionsCollection as $question){
                $question->setCategoryId(0)->save();
            }
            return parent::_afterDelete();
        }

        public function getFaqCollection()
        {
            $helper = Mage::helper('velesfaq');
            $collection = $helper->joinMyQuestionsCollection($this->getId());

            return $collection;
        }
    }
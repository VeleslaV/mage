<?php
    class Veles_News_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {

        protected function _construct()
        {
            $this->_blockGroup = 'velesnews';
            $this->_controller = 'adminhtml_category';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('velesnews');
            $model = Mage::registry('current_category');

            if ($model->getId()) {
                return $helper->__("Edit Category '%s'", $this->escapeHtml($model->getTitle()));
            } else {
                return $helper->__("Add Category");
            }
        }

    }
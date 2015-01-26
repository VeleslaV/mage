<?php
    class Veles_FAQ_Block_Adminhtml_Faq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'velesfaq';
            $this->_controller = 'adminhtml_faq';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('velesfaq');
            $model = Mage::registry('current_question');

            if ($model->getId()) {
                return $helper->__("Edit question item '%s'", $this->escapeHtml($model->getTitle()));
            } else {
                return $helper->__("Add question item");
            }
        }
    }
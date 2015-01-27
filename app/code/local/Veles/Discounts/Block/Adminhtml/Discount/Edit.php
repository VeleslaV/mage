<?php
    class Veles_Discounts_Block_Adminhtml_Discount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'veles_discounts';
            $this->_controller = 'adminhtml_discount';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('veles_discounts');
            $model = Mage::registry('current_customer');

            if ($model->getId()) {
                return $helper->__("Edit item '%s'", $this->escapeHtml($model->getCustomerId()));
            } else {
                return $helper->__("Add new item");
            }
        }
    }
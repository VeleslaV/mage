<?php
    class Veles_Credit_Block_Adminhtml_Customers_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'credit';
            $this->_controller = 'adminhtml_customers';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('credit');
            $model = Mage::registry('current_customer');

            if ($model->getId()) {
                return $helper->__("Edit News item '%s'", $this->escapeHtml($model->getTitle()));
            } else {
                return $helper->__("Add News item");
            }
        }
    }
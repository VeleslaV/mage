<?php
    class Veles_Fee_Block_Adminhtml_Customers_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'fee';
            $this->_controller = 'adminhtml_customers';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('fee');
            $model = Mage::registry('current_customer');

            if ($model->getId()) {
                return $helper->__("Edit News item '%s'", $this->escapeHtml($model->getTitle()));
            } else {
                return $helper->__("Add News item");
            }
        }
    }
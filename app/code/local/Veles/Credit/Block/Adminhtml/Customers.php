<?php
    class Veles_Credit_Block_Adminhtml_Customers extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('credit');
            $this->_blockGroup = 'credit';
            $this->_controller = 'adminhtml_customers';

            $this->_headerText = $helper->__('Module Customers Management');
            $this->_addButtonLabel = $helper->__('Add Customer');
        }
    }
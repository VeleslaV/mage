<?php
    class Veles_Discounts_Block_Adminhtml_Discount extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('veles_discounts');
            $this->_blockGroup = 'veles_discounts';
            $this->_controller = 'adminhtml_discount';

            $this->_headerText = $helper->__('Module Discounts Management');
            $this->_addButtonLabel = $helper->__('Add New Discount');
        }
    }
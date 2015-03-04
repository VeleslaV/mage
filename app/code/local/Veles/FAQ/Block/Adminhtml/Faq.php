<?php
    class Veles_FAQ_Block_Adminhtml_Faq extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('velesfaq');
            $this->_blockGroup = 'velesfaq';
            $this->_controller = 'adminhtml_faq';

            $this->_headerText = $helper->__('FAQ Management');
            $this->_addButtonLabel = $helper->__('Add Question');
        }
    }
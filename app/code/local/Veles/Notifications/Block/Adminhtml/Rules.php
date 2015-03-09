<?php
    class Veles_Notifications_Block_Adminhtml_Rules extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('veles_notifications');
            $this->_blockGroup = 'veles_notifications';
            $this->_controller = 'adminhtml_rules';

            $this->_headerText = $helper->__('Notification Rules Management');
            $this->_addButtonLabel = $helper->__('Add New Rule');
        }
    }
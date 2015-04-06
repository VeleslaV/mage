<?php
    class Veles_Notifications_Block_Adminhtml_Events extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('veles_notifications');
            $this->_blockGroup = 'veles_notifications';
            $this->_controller = 'adminhtml_events';
            $this->_headerText = $helper->__('Notification Events');
        }



        protected function _prepareLayout() {
            $this->_removeButton('add');

            return parent::_prepareLayout();
        }
    }
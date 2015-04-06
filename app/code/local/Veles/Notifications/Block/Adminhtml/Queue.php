<?php
    class Veles_Notifications_Block_Adminhtml_Queue extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        protected function _construct()
        {
            parent::_construct();

            $helper = Mage::helper('veles_notifications');
            $this->_blockGroup = 'veles_notifications';
            $this->_controller = 'adminhtml_queue';

            $this->_headerText = $helper->__('Notification Queue Information');
        }



        protected function _prepareLayout() {
            $this->_removeButton('add');

            return parent::_prepareLayout();
        }
    }
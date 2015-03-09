<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
    {
        public function __construct()
        {
            $helper = Mage::helper('veles_notifications');

            parent::__construct();
            $this->setId('rule_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle($helper->__('Rule Configuration'));
        }

        protected function _prepareLayout()
        {
            $helper = Mage::helper('veles_notifications');

            $this->addTab('general_section', array(
                'label' => $helper->__('General Rule Information'),
                'title' => $helper->__('General Rule Information'),
                'content' => $this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit_tabs_general')->toHtml(),
            ));
            $this->addTab('store_section', array(
                'label' => $helper->__('Stores And Customers Details'),
                'title' => $helper->__('Stores And Customers Details'),
                'content' => $this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit_tabs_storesAndCustomers')->toHtml(),
            ));
            $this->addTab('sender_section', array(
                'label' => $helper->__('Sender Details'),
                'title' => $helper->__('Sender Details'),
                'content' => $this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit_tabs_sender')->toHtml(),
            ));
            $this->addTab('schedule_section', array(
                'label' => $helper->__('Notification Schedule'),
                'title' => $helper->__('Notification Schedule'),
                'content' => $this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit_tabs_schedule')->toHtml(),
            ));
            return parent::_prepareLayout();
        }
    }
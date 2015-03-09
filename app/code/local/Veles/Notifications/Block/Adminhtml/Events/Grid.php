<?php
    class Veles_Notifications_Block_Adminhtml_Events_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('veles_notifications/event')->getCollection();

            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {
            $helper = Mage::helper('veles_notifications');

            $this->addColumn('event_id', array(
                'header' => $helper->__('Event ID'),
                'width' => '50px',
                'index' => 'event_id',
            ));

            $this->addColumn('event_title', array(
                'header' => $helper->__('Event Title'),
                'index' => 'event_title',
                'type' => 'text',
            ));

            $this->addColumn('event_status', array(
                'header' => $helper->__('Event Status'),
                'index' => 'event_status',
                'width' => '70px',
                'type' => 'options',
                'options' => $helper->getBaseStatusesOptions(),
            ));

            return parent::_prepareColumns();
        }
    }
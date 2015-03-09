<?php
    class Veles_Notifications_Block_Adminhtml_Queue_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $resource = Mage::getSingleton('core/resource');
            $collection = Mage::getModel('veles_notifications/queue')->getCollection();
            $collection->getSelect()
                ->join(
                    array('rule' => $resource->getTableName('veles_notifications/veles_notifications_rules_table')),
                    'main_table.rule_id = rule.rule_id',
                    'rule.rule_title'
                );

            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {
            $helper = Mage::helper('veles_notifications');

            $this->addColumn('queue_id', array(
                'header' => $helper->__('Queue ID'),
                'width' => '50px',
                'index' => 'queue_id',
            ));

            $this->addColumn('rule_title', array(
                'header' => $helper->__('Rule Title'),
                'index' => 'rule_title',
                'type' => 'text',
            ));

            $this->addColumn('order_id', array(
                'header' => $helper->__('Order Id'),
                'index' => 'order_id',
                'width' => '50px',
                'type' => 'text',
            ));

            $this->addColumn('customer_name', array(
                'header' => $helper->__('Customer Name'),
                'index' => 'customer_name',
                'type' => 'text',
            ));

            $this->addColumn('customer_email', array(
                'header' => $helper->__('Customer Email'),
                'index' => 'customer_email',
                'type' => 'text',
            ));

            $this->addColumn('created_at', array(
                'header' => $helper->__('Created At'),
                'width' => '140px',
                'index' => 'created_at',
                'type' => 'datetime',
            ));

            $this->addColumn('scheduled_at', array(
                'header' => $helper->__('Scheduled At'),
                'width' => '140px',
                'index' => 'scheduled_at',
                'type' => 'datetime',
            ));

            $this->addColumn('queue_status', array(
                'header' => $helper->__('Queue Status'),
                'index' => 'queue_status',
                'width' => '70px',
                'type' => 'options',
                'options' => $helper->getQueueStatusesOptions(),
            ));

            return parent::_prepareColumns();
        }
    }
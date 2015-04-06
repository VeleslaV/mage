<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $resource = Mage::getSingleton('core/resource');
            $collection = Mage::getModel('veles_notifications/rule')->getCollection();
            $collection->getSelect()
                ->join(
                    array('event' => $resource->getTableName('veles_notifications/veles_notifications_events_table')),
                    'main_table.event_id = event.event_id',
                    'event.event_title'
                )
                ->join(
                    array('group' => 'customer_group'),
                    'main_table.group_id = group.customer_group_id',
                    'group.customer_group_code'
                );

            $this->setCollection($collection);
            return parent::_prepareCollection();
        }



        protected function _prepareColumns()
        {
            $helper = Mage::helper('veles_notifications');

            $this->addColumn('rule_id', array(
                'header' => $helper->__('Rule ID'),
                'width' => '50px',
                'index' => 'rule_id',
            ));

            $this->addColumn('rule_title', array(
                'header' => $helper->__('Rule Title'),
                'index' => 'rule_title',
                'type' => 'text',
            ));

            $this->addColumn('event_title', array(
                'header' => $helper->__('Rule Event'),
                'index' => 'event_title',
                'type' => 'text',
            ));

            $this->addColumn('store_id', array(
                'header' => $helper->__('Store View'),
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => true,
            ));

            $this->addColumn('customer_group_code', array(
                'header' => $helper->__('Customers Group'),
                'index' => 'customer_group_code',
                'type' => 'text',
            ));

            $this->addColumn('rule_status', array(
                'header' => $helper->__('Rule Status'),
                'index' => 'rule_status',
                'width' => '70px',
                'type' => 'options',
                'options' => $helper->getBaseStatusesOptions(),
            ));

            $this->addColumn('action', array(
                'header'    => $helper->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $helper->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('id' => $this->getId())
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));

            return parent::_prepareColumns();
        }



        protected function _prepareMassaction()
        {
            $this->setMassactionIdField('rule_id');
            $this->getMassactionBlock()->setFormFieldName('rules');

            $this->getMassactionBlock()->addItem('delete', array(
                'label' => $this->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
            ));
            return $this;
        }



        public function getRowUrl($model)
        {
            return $this->getUrl('*/*/edit', array(
                'id' => $model->getId(),
            ));
        }
    }
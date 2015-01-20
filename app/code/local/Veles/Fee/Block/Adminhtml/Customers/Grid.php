<?php
    class Veles_Fee_Block_Adminhtml_Customers_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('fee/fee')->getCollection();
            $collection->getSelect()
                ->join(array('cq'=>'customer_entity'), 'main_table.customer_id = cq.entity_id', 'cq.email');


            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {
            $helper = Mage::helper('fee');

            $this->addColumn('entity_id', array(
                'header' => $helper->__('Entity ID'),
                'index' => 'entity_id'
            ));

            $this->addColumn('customer_id', array(
                'header' => $helper->__('Customer Id'),
                'index' => 'customer_id',
                'type' => 'text',
            ));

            $this->addColumn('email', array(
                'header' => Mage::helper('fee')->__('Customer Email'),
                'align' =>'left',
                'index' => 'email',
                'type' => 'text',

            ));

            $this->addColumn('credit_amount', array(
                'header' => $helper->__('Customer Credit Amount'),
                'index' => 'credit_amount',
                'type' => 'text',
            ));

            return parent::_prepareColumns();
        }

        protected function _prepareMassaction()
        {
            $this->setMassactionIdField('entity_id');
            $this->getMassactionBlock()->setFormFieldName('customers');

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
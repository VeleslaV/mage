<?php
    class Veles_FAQ_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('velesfaq/faq')->getCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {

            $helper = Mage::helper('velesfaq');

            $this->addColumn('question_id', array(
                'header' => $helper->__('ID'),
                'index' => 'question_id',
                'align' => 'center',
                'width' => '40px',
            ));

            $this->addColumn('user_email', array(
                'header' => $helper->__('User Email'),
                'index' => 'user_email',
                'type' => 'text',
                'width' => '200px',
            ));

            $this->addColumn('title', array(
                'header' => $helper->__('Title'),
                'index' => 'title',
                'type' => 'text',
            ));

            $this->addColumn('answer', array(
                'header' => $helper->__('Answer'),
                'index' => 'answer',
                'type' => 'text',
            ));

            $this->addColumn('status', array(
                'header' => $helper->__('Status'),
                'index' => 'status',
                'options' => $helper->getStatusesList(),
                'type'  => 'options',
                'width' => '350px',
            ));

            $this->addColumn('created', array(
                'header' => $helper->__('Created'),
                'index' => 'created',
                'type' => 'date',
            ));

            return parent::_prepareColumns();
        }

        protected function _prepareMassaction()
        {
            $this->setMassactionIdField('question_id');
            $this->getMassactionBlock()->setFormFieldName('faq');

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
<?php
    class Veles_FAQ_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('velesfaq/category')->getCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {

            $helper = Mage::helper('velesfaq');

            $this->addColumn('category_id', array(
                'header' => $helper->__('Category ID'),
                'index' => 'category_id'
            ));

            $this->addColumn('title', array(
                'header' => $helper->__('Title'),
                'index' => 'title',
                'type' => 'text',
            ));

            $this->addColumn('link', array(
                'header' => $helper->__('Link'),
                'index' => 'link',
                'type' => 'text',
            ));

            return parent::_prepareColumns();
        }

        protected function _prepareMassaction()
        {
            $this->setMassactionIdField('category_id');
            $this->getMassactionBlock()->setFormFieldName('category');

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
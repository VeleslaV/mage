<?php
    class Veles_News_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {

        protected function _prepareCollection()
        {
            $this->setCollection(Mage::getModel('velesnews/category')->getCollection());
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {

            $helper = Mage::helper('velesnews');

            $this->addColumn('category_id', array(
                'header' => $helper->__('Category ID'),
                'index' => 'category_id'
            ));

            $this->addColumn('title', array(
                'header' => $helper->__('Title'),
                'index' => 'title',
                'type' => 'text',
            ));

            return parent::_prepareColumns();
        }

        public function getRowUrl($model)
        {
            return $this->getUrl('*/*/edit', array(
                'id' => $model->getId(),
            ));
        }

    }
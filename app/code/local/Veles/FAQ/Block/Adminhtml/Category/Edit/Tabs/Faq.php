<?php
    class Veles_FAQ_Block_Adminhtml_Category_Edit_Tabs_Faq extends Mage_Adminhtml_Block_Widget_Grid
    {
        public function __construct()
        {
            parent::__construct();
            $this->setId('categoryFaqGrid');
            $this->setUseAjax(true);
        }

        protected function _prepareCollection()
        {
            $collection = Mage::registry('current_category')->getFaqCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {
            $helper = Mage::helper('velesfaq');

            $this->addColumn('ajax_grid_question_id', array(
                'header' => $helper->__('ID'),
                'index' => 'question_id',
                'align' => 'center',
                'width' => '40px',
            ));

            $this->addColumn('ajax_grid_user_email', array(
                'header' => $helper->__('User Email'),
                'index' => 'user_email',
                'type' => 'text',
            ));

            $this->addColumn('ajax_grid_title', array(
                'header' => $helper->__('Title'),
                'index' => 'title',
                'type' => 'text',
            ));

            $this->addColumn('ajax_grid_status', array(
                'header' => $helper->__('Status'),
                'index' => 'status',
                'options' => $helper->getStatusesList(),
                'type'  => 'options',
                'width' => '350px',
            ));

            $this->addColumn('ajax_grid_created', array(
                'header' => $helper->__('Created'),
                'index' => 'created',
                'type' => 'date',
            ));

            return parent::_prepareColumns();
        }

        public function getGridUrl()
        {
            return $this->getUrl('*/*/faq', array('_current' => true, 'grid_only' => 1));
        }

        public function getRowUrl($model)
        {
            return $this->getUrl('*/adminhtml_faq/edit', array(
                'id' => $model->getId(),
            ));
        }
    }
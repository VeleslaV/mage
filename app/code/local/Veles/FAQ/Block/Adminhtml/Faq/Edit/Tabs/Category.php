<?php
    class Veles_FAQ_Block_Adminhtml_Faq_Edit_Tabs_Category extends Mage_Adminhtml_Block_Widget_Grid
    {
        public function __construct()
        {
            parent::__construct();
            $this->setId('faqCategoryGrid');
            $this->setSaveParametersInSession(false);
            $this->setUseAjax(true);
        }

        protected function _prepareCollection()
        {
            $collection = Mage::getModel('velesfaq/category')->getCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }

        protected function _prepareColumns()
        {
            $helper = Mage::helper('velesfaq');

            $this->addColumn('ajax_grid_questions_in', array(
                'align' => 'center',
                'header_css_class' => 'a-center',
                'index' => 'category_id',
                'type' => 'checkbox',
                'values' => $this->getSelectedCategories(),
            ));

            $this->addColumn('ajax_grid_category_id', array(
                'header' => $helper->__('Category ID'),
                'index' => 'category_id',
                'width' => '100px',
            ));

            $this->addColumn('ajax_grid_title', array(
                'header' => $helper->__('Title'),
                'index' => 'title',
                'type' => 'text',
            ));

            return parent::_prepareColumns();
        }

        protected function _addColumnFilterToCollection($column)
        {
            if ($column->getId() == 'ajax_grid_questions_in') {
                $collection = $this->getCollection();
                $selectedCategories = $this->getSelectedCategories();
                if ($column->getFilter()->getValue()) {
                    $collection->addFieldToFilter('category_id', array('in' => $selectedCategories));
                } elseif (!empty($selectedCategories)) {
                    $collection->addFieldToFilter('category_id', array('nin' => $selectedCategories));
                }
            } else {
                parent::_addColumnFilterToCollection($column);
            }
            return $this;
        }

        public function getGridUrl()
        {
            return $this->getUrl('*/*/category', array('_current' => true, 'grid_only' => 1));
        }

        public function getRowUrl($model)
        {
            return $this->getUrl('*/adminhtml_category/edit', array(
                'id' => $model->getId(),
            ));
        }

        public function getSelectedCategories()
        {
            if (!isset($this->_data['selected_categories'])) {
                $selectedCategories = Mage::app()->getRequest()->getParam('selected_categories', null);

                if(is_null($selectedCategories) || !is_array($selectedCategories)){
                    $question = Mage::registry('current_question');
                    $selectedCategories = $question->getCategoriesCollection()->getAllIds();
                }
                $this->_data['selected_categories'] = $selectedCategories;
            }
            return $this->_data['selected_categories'];
        }

    }
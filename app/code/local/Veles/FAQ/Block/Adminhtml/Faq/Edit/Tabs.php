<?php
    class Veles_FAQ_Block_Adminhtml_Faq_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
    {
        public function __construct()
        {
            $helper = Mage::helper('velesfaq');

            parent::__construct();
            $this->setId('faq_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle($helper->__('Questions Information'));
        }

        protected function _prepareLayout()
        {
            $helper = Mage::helper('velesfaq');
            $question = Mage::registry('current_question');

            $this->addTab('general_section', array(
                'label' => $helper->__('General Information'),
                'title' => $helper->__('General Information'),
                'content' => $this->getLayout()->createBlock('velesfaq/adminhtml_faq_edit_tabs_general')->toHtml(),
            ));
            if($question->getId()){
                $this->addTab('category_section', array(
                    'class' => 'ajax',
                    'label' => $helper->__('Categories'),
                    'title' => $helper->__('Categories'),
                    'url' => $this->getUrl('*/*/category', array('_current' => true)),
                ));
            }

            return parent::_prepareLayout();
        }
    }
<?php
    class Veles_News_Block_Adminhtml_News_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
    {

        public function __construct()
        {
            $helper = Mage::helper('velesnews');

            parent::__construct();
            $this->setId('news_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle($helper->__('News Information'));
        }

        protected function _prepareLayout()
        {
            $helper = Mage::helper('velesnews');

            $this->addTab('general_section', array(
                'label' => $helper->__('General Information'),
                'title' => $helper->__('General Information'),
                'content' => $this->getLayout()->createBlock('velesnews/adminhtml_news_edit_tabs_general')->toHtml(),
            ));
            $this->addTab('custom_section', array(
                'label' => $helper->__('Custom Fields'),
                'title' => $helper->__('Custom Fields'),
                'content' => $this->getLayout()->createBlock('velesnews/adminhtml_news_edit_tabs_custom')->toHtml(),
            ));
            return parent::_prepareLayout();
        }

    }
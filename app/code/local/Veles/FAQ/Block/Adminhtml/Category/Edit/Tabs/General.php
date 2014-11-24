<?php
    class Veles_FAQ_Block_Adminhtml_Category_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('velesfaq');
            $model = Mage::registry('current_category');


            $form = new Varien_Data_Form();
            $fieldset = $form->addFieldset('category_form', array('legend' => $helper->__('Category Information')));

            $fieldset->addField('title', 'text', array(
                'label' => $helper->__('Title'),
                'required' => true,
                'name' => 'title',
            ));

            $fieldset->addField('link', 'text', array(
                'label' => $helper->__('Link'),
                'required' => true,
                'name' => 'link',
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
<?php
    class Veles_News_Block_Adminhtml_Category_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
    {

        protected function _prepareForm()
        {
            $helper = Mage::helper('velesnews');
            $model = Mage::registry('current_category');

            $form = new Varien_Data_Form();
            $fieldset = $form->addFieldset('general_form', array('legend' => $helper->__('General Information')));

            $fieldset->addField('title', 'text', array(
                'label' => $helper->__('Title'),
                'required' => true,
                'name' => 'title',
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
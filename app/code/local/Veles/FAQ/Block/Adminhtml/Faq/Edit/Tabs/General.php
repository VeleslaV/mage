<?php
    class Veles_FAQ_Block_Adminhtml_Faq_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('velesfaq');
            $model = Mage::registry('current_question');

            $form = new Varien_Data_Form();
            $fieldset = $form->addFieldset('question_form', array('legend' => $helper->__('Question Information')));

            $fieldset->addField('user_email', 'text', array(
                'label' => $helper->__('User Email'),
                'required' => true,
                'name' => 'user_email',
            ));

            $fieldset->addField('title', 'text', array(
                'label' => $helper->__('Title'),
                'required' => true,
                'name' => 'title',
            ));

            $fieldset->addField('question', 'editor', array(
                'label' => $helper->__('Question'),
                'required' => true,
                'name' => 'question',
            ));

            $fieldset->addField('answer', 'editor', array(
                'label' => $helper->__('Answer'),
                'required' => false,
                'name' => 'answer',
            ));

            $fieldset->addField('status', 'select', array(
                'label' => $helper->__('Status'),
                'name' => 'status',
                'values' => $helper->getStatusesOptions(),
                'required' => true,
            ));

            $fieldset->addField('created', 'date', array(
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'label' => $helper->__('Created'),
                'name' => 'created'
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
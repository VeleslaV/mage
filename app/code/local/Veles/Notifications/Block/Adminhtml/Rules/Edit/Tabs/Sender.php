<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs_Sender extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');

            $form = new Varien_Data_Form();
            $fieldset = $form->addFieldset('sender_rule_form', array('legend' => $helper->__('Edit Rule. Sender Information')));

            $fieldset->addField('sender_name', 'text', array(
                'label' => $helper->__('Sender Name'),
                'required' => true,
                'class'     => 'required-entry',
                'name' => 'sender_name',
            ));

            $fieldset->addField('sender_email', 'text', array(
                'label' => $helper->__('Sender Email'),
                'required' => true,
                'class'     => 'required-entry validate-email',
                'name' => 'sender_email',
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
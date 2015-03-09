<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs_Schedule extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');

            $form = new Varien_Data_Form();

            $fieldset = $form->addFieldset('schedule_rule_form', array('legend' => $helper->__('Edit Rule. Notification Schedule')));

            $fieldset->addField('send_at', 'text', array(
                'label' => $helper->__('Send At'),
                'required' => true,
                'class'     => 'required-entry',
                'name' => 'send_at',
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs_Schedule extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');

            $form = new Varien_Data_Form();

            $fieldset = $form->addFieldset('schedule_rule_form', array(
                'legend' => $helper->__('Edit Rule. Notification Schedule'))
            );

            $fieldset->addField('send_at', 'select', array(
                'label' => $helper->__('Send At'),
                'required' => true,
                'class'     => 'required-entry',
                'name' => 'send_at',
                'values' => $helper->getScheduleOptions(),
            ));

            $fieldset->addField('email_template_id', 'select', array(
                'label' => $helper->__('Email Template'),
                'required' => true,
                'class'     => 'required-entry',
                'name' => 'email_template_id',
                'values' => $helper->getEmailTemplatesOptions(),
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');

            $form = new Varien_Data_Form();

            $fieldset = $form->addFieldset('general_rule_form', array('legend' => $helper->__('Edit Rule. General Information')));

            $fieldset->addField('rule_title', 'text', array(
                'label' => $helper->__('Rule Title'),
                'required' => true,
                'name' => 'rule_title',
            ));

            $fieldset->addField('event_id', 'select', array(
                'label' => $helper->__('Rule Event'),
                'required' => true,
                'name' => 'event_id',
                'values' => $helper->getRuleEvents(),
            ));

            $fieldset->addField('rule_status', 'select', array(
                'label' => $helper->__('Rule Status'),
                'required' => true,
                'name' => 'rule_status',
                'values' => $helper->getBaseStatuses(),
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
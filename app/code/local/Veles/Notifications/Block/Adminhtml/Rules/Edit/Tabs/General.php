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
                'values' => $helper->getRuleEvents("start"),
            ));

            $fieldset->addField('cancel_event_id', 'select', array(
                'label' => $helper->__('Rule Cancel Event'),
                'required' => false,
                'name' => 'cancel_event_id',
                'values' => $helper->getRuleEvents("cancel"),
            ));

            $fieldset->addField('consider_quantity', 'select', array(
                'label' => $helper->__('Consider quantity of bought products?'),
                'required' => true,
                'name' => 'consider_quantity',
                'values' => $helper->getBaseStatuses(),
            ));

            $fieldset->addField('products_count', 'text', array(
                'label' => $helper->__('Number of products purchased for discounts'),
                'required' => false,
                'name' => 'products_count',
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

        protected function _toHtml()
        {
            $dependency_block = $this->getLayout()
                ->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap('products_count', 'products_count')
                ->addFieldMap('consider_quantity', 'consider_quantity')
                ->addFieldDependence('products_count', 'consider_quantity', '1');
            return parent::_toHtml() . $dependency_block->toHtml();
        }
    }
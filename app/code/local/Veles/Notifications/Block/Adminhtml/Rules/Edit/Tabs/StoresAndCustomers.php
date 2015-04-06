<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit_Tabs_StoresAndCustomers extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');
            $form = new Varien_Data_Form();

            $storeFieldset = $form->addFieldset('stores_rule_form', array(
                'legend' => $helper->__('Edit Rule. Store View Information'))
            );

            if (!Mage::app()->isSingleStoreMode()) {
                $storeFieldset->addField('store_id', 'select', array(
                    'name' => 'store_id',
                    'label' => $helper->__('Store View'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                ));
            } else {
                $storeFieldset->addField('store_id', 'hidden', array(
                    'name' => 'store_id',
                    'value' => Mage::app()->getStore(true)->getId(),
                ));
            }

            $groupFieldset = $form->addFieldset('groups_rule_form', array(
                'legend' => $helper->__('Edit Rule. Customers Group Information'))
            );
            $groupFieldset->addField('group_id', 'select', array(
                'label' => $helper->__('Customers Group'),
                'required' => true,
                'name' => 'group_id',
                'values' => $helper->getCustomersGroups(),
            ));

            $form->setValues($model->getData());
            $this->setForm($form);

            return parent::_prepareForm();
        }
    }
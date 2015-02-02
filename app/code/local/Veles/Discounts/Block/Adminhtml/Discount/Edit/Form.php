<?php
    class Veles_Discounts_Block_Adminhtml_Discount_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('veles_discounts');
            $model = Mage::registry('current_customer');

            $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array(
                        'id' => $this->getRequest()->getParam('id')
                    )),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ));

            $this->setForm($form);

            $fieldset = $form->addFieldset('customer_form', array('legend' => $helper->__('Customer Discount Information')));

            $fieldset->addField('customer_id', 'text', array(
                'label' => $helper->__('Customer Id'),
                'required' => true,
                'name' => 'customer_id',
            ));
            $fieldset->addField('customer_orders_quantity', 'text', array(
                'label' => $helper->__('Customer Orders Quantity'),
                'required' => true,
                'name' => 'customer_orders_quantity',
            ));
            $fieldset->addField('customer_orders_value', 'text', array(
                'label' => $helper->__('Customer Orders Value'),
                'required' => true,
                'name' => 'customer_orders_value',
            ));
            $fieldset->addField('customer_discount_coupon', 'text', array(
                'label' => $helper->__('Customer Discount Coupon'),
                'required' => true,
                'name' => 'customer_discount_coupon',
            ));

            $form->setUseContainer(true);

            if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
                $form->setValues($data);
            } else {
                $form->setValues($model->getData());
            }

            return parent::_prepareForm();
        }
    }
<?php
    class Veles_Fee_Block_Adminhtml_Customers_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('fee');
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

            $fieldset = $form->addFieldset('customer_form', array('legend' => $helper->__('Customer Credit Information')));

            $fieldset->addField('credit_amount', 'text', array(
                'label' => $helper->__('Customer Credit Amount'),
                'required' => true,
                'name' => 'credit_amount',
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
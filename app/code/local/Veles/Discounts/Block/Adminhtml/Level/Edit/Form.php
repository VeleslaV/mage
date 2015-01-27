<?php
    class Veles_Discounts_Block_Adminhtml_Level_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('veles_discounts');
            $model = Mage::registry('current_level');

            $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array(
                        'id' => $this->getRequest()->getParam('id')
                    )),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ));

            $this->setForm($form);

            $fieldset = $form->addFieldset('level_form', array('legend' => $helper->__('Customer Discount Information')));

            $fieldset->addField('level', 'text', array(
                'label' => $helper->__('Level'),
                'required' => true,
                'name' => 'level',
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
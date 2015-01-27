<?php
    class Veles_Discounts_Block_Adminhtml_Level_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'veles_discounts';
            $this->_controller = 'adminhtml_level';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('veles_discounts');
            $model = Mage::registry('current_level');

            if ($model->getId()) {
                return $helper->__("Edit level '%s'", $this->escapeHtml($model->getEntityId()));
            } else {
                return $helper->__("Add new level");
            }
        }
    }
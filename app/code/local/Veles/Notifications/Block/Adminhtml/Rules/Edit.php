<?php
    class Veles_Notifications_Block_Adminhtml_Rules_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        protected function _construct()
        {
            $this->_blockGroup = 'veles_notifications';
            $this->_controller = 'adminhtml_rules';
        }

        public function getHeaderText()
        {
            $helper = Mage::helper('veles_notifications');
            $model = Mage::registry('current_rule');

            if ($model->getId()) {
                return $helper->__("Edit rule '%s'", $this->escapeHtml($model->getRuleTitle()));
            } else {
                return $helper->__("Add new rule");
            }
        }
    }
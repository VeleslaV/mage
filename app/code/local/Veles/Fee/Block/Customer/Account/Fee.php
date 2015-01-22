<?php
    class Veles_Fee_Block_Customer_Account_Fee extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $itemsCollection = Mage::getModel('fee/fee')->getTotalFee();
            $this->assign('customer_credits', $itemsCollection);

            return parent::_toHtml();
        }
    }
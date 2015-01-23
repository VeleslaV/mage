<?php
    class Veles_Credit_Block_Customer_Account_Credit extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $itemsCollection = Mage::getModel('credit/credit')->getTotalCredits();
            $this->assign('customer_credits', $itemsCollection);

            return parent::_toHtml();
        }
    }
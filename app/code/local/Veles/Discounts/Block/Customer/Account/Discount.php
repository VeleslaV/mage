<?php
    class Veles_Discounts_Block_Customer_Account_Discount extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $customerDiscountPercent = Mage::getModel('veles_discounts/discount')->getCustomerDiscountPercent();
            $this->setCustomerDiscount($customerDiscountPercent);

            return parent::_toHtml();
        }
    }
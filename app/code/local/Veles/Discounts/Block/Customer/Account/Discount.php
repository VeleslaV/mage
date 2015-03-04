<?php
    class Veles_Discounts_Block_Customer_Account_Discount extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $discountModel = Mage::getModel('veles_discounts/discount')->load($customerData->getId());

            $customerDiscountPercent = $discountModel->getCustomerDiscountPercent($customerData->getId());

            $helper = Mage::helper('veles_discounts');
            $amount = $helper->getDiscountConditionValue($discountModel->getCustomerOrdersQuantity(), $discountModel->getCustomerOrdersValue());
            $customerDiscountLevel = $helper->getDiscountLevelByAmount($amount);

            $this->setCustomerDiscount($customerDiscountPercent);
            $this->setDiscountLevel($customerDiscountLevel);

            return parent::_toHtml();
        }
    }
<?php
    class Veles_Discounts_Block_Customer_Account_Discount extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $discountModel = Mage::getModel('veles_discounts/discount')->load($customerData->getId());
            $customerDiscountPercent = $discountModel->getCustomerDiscountPercent();
            $customerDiscountLevel = $discountModel->getCustomerDiscountLevel();
            $customerCouponeCode = $discountModel->getCustomerDiscountCoupon();


            $this->setCustomerDiscount($customerDiscountPercent);
            $this->setCouponCode($customerCouponeCode);
            $this->setDiscountLevel($customerDiscountLevel);

            return parent::_toHtml();
        }
    }
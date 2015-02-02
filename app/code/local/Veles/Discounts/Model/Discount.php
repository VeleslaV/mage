<?php
    class Veles_Discounts_Model_Discount extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discounts/discount');
        }

        public function getCustomerDiscountPercent()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $costumerDiscountData = Mage::getModel('veles_discounts/discount')->load($customerData->getId());
                $customerDiscountLevel = $this->getDiscountLevel();
                $customerDiscount = $this->getDiscountPercentByLevel($customerDiscountLevel);
            }else{
                $customerDiscount = 0;
            }

            return $customerDiscount;
        }

        public function getDiscountPercentByLevel($level)
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $helper = Mage::helper('veles_discounts');
                $discountMethod = $helper->getDiscountMethod();

                if($discountMethod == "for_quantity"){
                    $discountData = $helper->getDiscountForQuantityData();
                }else{
                    $discountData = $helper->getDiscountForTotalData();
                }

                if($discountData[$level]){
                    $resultDiscountPercent = $discountData[$level]['level_discount'];
                }else{
                    $resultDiscountPercent = 0;
                }
            }else{
                $resultDiscountPercent = 0;
            }

            return $resultDiscountPercent;
        }

        public function getDiscountLevel()
        {
            $customerDiscountLevel = 0;
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $customerId = $customerData->getId();

                $costumerDiscountData = Mage::getModel('veles_discounts/discount')->load($customerId);
                $helper = Mage::helper('veles_discounts');
                $discountMethod = $helper->getDiscountMethod();

                if($discountMethod == "for_quantity"){
                    $discountData = $helper->getDiscountForQuantityData();
                    $condition = $costumerDiscountData->getCustomerOrdersQuantity();
                }else{
                    $discountData = $helper->getDiscountForTotalData();
                    $condition = $costumerDiscountData->getCustomerOrdersValue();
                }

                arsort($discountData);
                foreach($discountData as $discountDataValue){
                    if($condition >= $discountDataValue['level_activate_on']){
                        $customerDiscountLevel = $discountDataValue['level_id'];
                        break;
                    }
                }
            }
//            echo "<pre>".print_r($discountData, true)."</pre>";

            return $customerDiscountLevel;
        }

        public function canApply()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $costumerDiscountData = Mage::getModel('veles_discounts/discount')->load($customerData->getId());
                $discountCoupon = $costumerDiscountData->getData('customer_discount_coupon');

                if(empty($discountCoupon)){
                    $applyResult = false;
                }else{
                    $applyResult = true;
                }
            }else{
                $applyResult = false;
            }

            return $applyResult;
        }
    }
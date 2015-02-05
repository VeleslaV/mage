<?php
    class Veles_Discounts_Model_Discount extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_discounts/discount');
        }

        public function getCustomerDiscountPercent($customerId)
        {
            $helper = Mage::helper('veles_discounts');

            $costumerDiscountData = $this->load($customerId);
            $condition = $helper->getDiscountConditionValue($costumerDiscountData->getCustomerOrdersQuantity(), $costumerDiscountData->getCustomerOrdersValue());

            $customerDiscountLevel = $helper->getDiscountLevelByAmount($condition);
            $customerDiscountPercent = $helper->getDiscountPercentByLevel($customerDiscountLevel);

            return $customerDiscountPercent;
        }

        public function canApply($customerId)
        {
            $costumerDiscountData = $this->load($customerId);

            if(sizeof($costumerDiscountData->getData())>0){
                $applyResult = true;
            }else{
                $applyResult = false;
            }

            return $applyResult;
        }
    }
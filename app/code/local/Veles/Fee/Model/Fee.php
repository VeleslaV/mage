<?php
    class Veles_Fee_Model_Fee extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('fee/fee');
        }

        public static function getTotalFee()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $costumerCreditCollection = Mage::getModel('fee/fee')->load($customerData->getId());
                $resultCostumerCredit = $costumerCreditCollection->getData('credit_amount');
            }else{
                $resultCostumerCredit = 0;
            }

            return $resultCostumerCredit;
        }

        public static function getFee()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $quote = Mage::getSingleton('checkout/cart')->getQuote();
                $resultCostumerFee = $quote->getData("fee_amount");
            }else{
                $resultCostumerFee = 0;
            }

            return $resultCostumerFee;
        }

        public static function canApply()
        {
            if((Veles_Fee_Model_Fee::getTotalFee()>0) AND (Veles_Fee_Model_Fee::getTotalFee() >= Veles_Fee_Model_Fee::getFee())){
                $thisResult = true;
            }else{
                $thisResult = false;
            }

            return $thisResult;
        }

    }
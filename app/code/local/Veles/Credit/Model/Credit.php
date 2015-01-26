<?php
    class Veles_Credit_Model_Credit extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('credit/credit');
        }

        public static function getTotalCredits()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $costumerCreditCollection = Mage::getModel('credit/credit')->load($customerData->getId());
                $resultCostumerCredit = $costumerCreditCollection->getData('credit_amount');
            }else{
                $resultCostumerCredit = 0;
            }

            return $resultCostumerCredit;
        }

        public static function getCredit()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $quote = Mage::getSingleton('checkout/cart')->getQuote();
                $resultCostumerCredit = $quote->getData("credit_amount");
            }else{
                $resultCostumerCredit = 0;
            }

            return $resultCostumerCredit;
        }

        public static function canApply()
        {
            if((Veles_Credit_Model_Credit::getTotalCredits()>0) AND (Veles_Credit_Model_Credit::getTotalCredits() >= Veles_Credit_Model_Credit::getCredit())){
                $thisResult = true;
            }else{
                $thisResult = false;
            }

            return $thisResult;
        }

    }
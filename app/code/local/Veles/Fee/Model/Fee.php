<?php
    class Veles_Fee_Model_Fee extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('fee/fee');
        }

        public static function getFee()
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

        public static function canApply($address)
        {
//            echo Mage::getStoreConfig('sales/cc_settings/first_opt')." - ".Mage::getStoreConfig('sales/cc_settings/second_opt');

            if(Veles_Fee_Model_Fee::getFee()>0){
                $thisResult = true;
            }else{
                $thisResult = false;
            }

            return $thisResult;
        }

    }
<?php
    class Veles_CustomerCredit_Model_Credit extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('velescustomercredit/credit');
        }

        public static function getCustomerCredit()
        {
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $costumerCreditCollection = Mage::getModel('velescustomercredit/credit')->load($customerData->getId());

                $resultCostumerCredit = $costumerCreditCollection->getData('credit_amount');
            }else{
                $resultCostumerCredit = 0;
            }

            return $resultCostumerCredit;
        }

        public static function canApply($address)
        {
            $thisModel = Mage::getSingleton('velescustomercredit/credit');
            if($thisModel->getCustomerCredit()>0){
                $thisResult = true;
            }else{
                $thisResult = false;
            }
//                echo"<pre>".print_r($address->getQuote()->getCustomerCreditAmount(), true)."<pre>";
//             Example of data retrieved :
//             $address->getShippingMethod(); > flatrate_flatrate
//             $address->getQuote()->getPayment()->getMethod(); > checkmo
//             $address->getCountryId(); > US
//             $address->getQuote()->getCouponCode(); > COUPONCODE

            return $thisResult;

        }
    }
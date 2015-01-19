<?php
    class Veles_CustomerCredit_Model_CustomerCredit extends Varien_Object
    {
        const CUSTOMERCREDIT_AMOUNT = -40;

        public static function getCustomerCredit()
        {
            return self::CUSTOMERCREDIT_AMOUNT;
        }

        public static function canApply($address)
        {
//            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
//                $customerData = Mage::getSingleton('customer/session')->getCustomer();
//                echo $customerData->getId();
//            }
//            Put here your business logic to check if customer credit should be applied or not

            return true;
        }
    }
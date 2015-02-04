<?php
    class Veles_Discounts_Helper_Data extends Mage_Core_Helper_Abstract
    {
        const XML_PATH_SIMPLE_EMAIL_TEMPLATE = 'simple/send_email/template';

        public function getDiscountMethod()
        {
            $resultOption = Mage::getStoreConfig('discounts_options/rules/discount_method_rule');

            return $resultOption;
        }

        public function getDiscountForQuantityData()
        {
            $serializedOptions = Mage::getStoreConfig('discounts_options/rules/levels_for_quantity');
            $optionsForQuantityArray = unserialize($serializedOptions);

            $rebuiltArray = $this->arrayRebuild($optionsForQuantityArray);

            return $rebuiltArray;
        }

        public function getDiscountForTotalData()
        {
            $serializedOptions = Mage::getStoreConfig('discounts_options/rules/levels_for_total');
            $optionsForTotalArray = unserialize($serializedOptions);

            $rebuiltArray = $this->arrayRebuild($optionsForTotalArray);

            return $rebuiltArray;
        }

        public function getDiscountAmount($price, $discount_percent)
        {
            $discount_amount = (($price / 100) * $discount_percent) * -1;

            return $discount_amount;
        }

        public function arrayRebuild($array)
        {
            $resultArray = array();
            $rebuiltArray = array();

            foreach($array as $optionKey => $optionValue){
                $rebuiltArray[$optionKey] = array_filter($optionValue);
            }

            foreach($rebuiltArray['level_id'] as $rebuiltValue){
                $resultArray[$rebuiltValue]['level_id'] = $rebuiltArray['level_id'][$rebuiltValue];
                $resultArray[$rebuiltValue]['level_title'] = $rebuiltArray['level_title'][$rebuiltValue];
                $resultArray[$rebuiltValue]['level_activate_on'] = $rebuiltArray['level_activate_on'][$rebuiltValue];
                $resultArray[$rebuiltValue]['level_discount'] = $rebuiltArray['level_discount'][$rebuiltValue];
            }

            return $resultArray;
        }

        public function sendEmailNotification($customerEmail, $couponeCode)
        {
            $templateId = Mage::getStoreConfig(self::XML_PATH_SIMPLE_EMAIL_TEMPLATE);

            $sender = array(
                'name' => 'MageStore',
                'email' => 'robot@mage.loc'
            );

            $email = "veleslav.ck@gmail.com";
//            $email = $customerEmail;
            $emailSubject = 'New Discount Level Notification';

            $customerAccountViewUrl = Mage::getUrl('customer/account/index');
            $vars = array(
                "account_link"=>"".$customerAccountViewUrl."",
                "coupone_code"=>"".$couponeCode.""
            );
            $storeId = Mage::app()->getStore()->getId();
            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $email, $emailSubject, $vars, $storeId);

        }
    }
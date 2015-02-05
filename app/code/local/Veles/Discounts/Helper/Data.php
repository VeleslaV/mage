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

        public function getCurrentDiscountData()
        {
            $discountMethod = $this->getDiscountMethod();
            if($discountMethod == "for_quantity"){
                $currentDiscountData = $this->getDiscountForQuantityData();
            }else{
                $currentDiscountData = $this->getDiscountForTotalData();
            }

            return $currentDiscountData;
        }

        public function getDiscountConditionValue($quantityValue, $totalValue)
        {
            $discountMethod = $this->getDiscountMethod();

            if($discountMethod == "for_quantity"){
                $conditionAmount = $quantityValue;
            }else{
                $conditionAmount = $totalValue;
            }

            return $conditionAmount;
        }

        public function getDiscountAmount($price, $discount_percent)
        {
            $discount_amount = (($price / 100) * $discount_percent) * -1;

            return $discount_amount;
        }

        public function getDiscountLevelByAmount($amount)
        {
            $discountLevel = 0;
            $discountData = $this->getCurrentDiscountData();

            arsort($discountData);
            foreach($discountData as $discountDataValue){
                if($amount >= $discountDataValue['level_activate_on']){
                    $discountLevel = $discountDataValue['level_id'];
                    break;
                }
            }

            return $discountLevel;
        }

        public function getDiscountPercentByLevel($level)
        {
            $discountData = $this->getCurrentDiscountData();

            if($discountData[$level]){
                $resultDiscountPercent = $discountData[$level]['level_discount'];
            }else{
                $resultDiscountPercent = 0;
            }

            return $resultDiscountPercent;
        }

        public function arrayRebuild($array)
        {
            $resultArray = array();
            $i = 1;
            foreach($array as $arrayKey => $arrayValue){
                $resultArray[$i] = $array[$arrayKey];
                $resultArray[$i] = $array[$arrayKey];
                $resultArray[$i] = $array[$arrayKey];
                $resultArray[$i] = $array[$arrayKey];

                $i++;
            }

            return $resultArray;
        }

        public function sendEmailNotification($customerEmail)
        {
            $templateId = Mage::getStoreConfig(self::XML_PATH_SIMPLE_EMAIL_TEMPLATE);

            $sender = array(
                'name' => 'Magento Store',
                'email' => 'robot@mage.loc'
            );

            $email = $customerEmail;
            $emailSubject = 'New Discount Level Notification';

            $customerAccountViewUrl = Mage::getUrl('customer/account/index');
            $vars = array(
                "account_link"=>"".$customerAccountViewUrl.""
            );
            $storeId = Mage::app()->getStore()->getId();
            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $email, $emailSubject, $vars, $storeId);

        }

        public function getCustomersList()
        {
            $collection = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToSelect('firstname')
                ->addAttributeToSelect('lastname')
                ->addAttributeToSelect('email');

            $options = array();
            $options[] = array(
                'label' => '',
                'value' => ''
            );

            foreach ($collection as $item) {
                $options[] = array(
                    'label' => $item->getData("firstname")." ".$item->getData("lastname"),
                    'value' => $item->getId(),
                );
            }
            return $options;
        }
    }
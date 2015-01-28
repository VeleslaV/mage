<?php
    class Veles_Discounts_Helper_Data extends Mage_Core_Helper_Abstract
    {
        public function getDiscountMethod()
        {
            $resultOption = Mage::getStoreConfig('discounts_options/rules/discount_method_rule');

            return $resultOption;
        }

        public function getDiscountForQuantityOptions()
        {
            $serializedOptions = Mage::getStoreConfig('discounts_options/rules/levels_for_quantity');
            $optionsForQuantityArray = unserialize($serializedOptions);

            return $optionsForQuantityArray;
        }

        public function getDiscountForTotalOptions()
        {
            $serializedOptions = Mage::getStoreConfig('discounts_options/rules/levels_for_total');
            $optionsForTotalArray = unserialize($serializedOptions);

            return $optionsForTotalArray;
        }
    }
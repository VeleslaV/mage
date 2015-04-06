<?php

    /**
     * Class Veles_Notifications_Model_ShoppingCartPriceRules
     */
    class Veles_Notifications_Model_ShoppingCartPriceRules
    {
        /**
         * @return array
         */
        public function toOptionArray()
        {
            /** @var Veles_Notifications_Helper_Data $helper */
            $helper = Mage::helper('veles_notifications');

            $rulesCollection = Mage::getResourceModel('salesrule/rule_collection');

            $options = $helper->createDropDownOptions(
                $rulesCollection, array('valueParam' => 'rule_id', 'labelParam' => 'name')
            );

            return $options;
        }
    }
<?php
    class Veles_Discounts_Model_DiscountRules
    {
        public function toOptionArray()
        {
            return array(
                array('value'=>"", 'label'=>""),
                array('value'=>"for_quantity", 'label'=>Mage::helper('veles_discounts')->__('For quantity of orders')),
                array('value'=>"for_total_value", 'label'=>Mage::helper('veles_discounts')->__('For total orders value')),
            );
        }
    }
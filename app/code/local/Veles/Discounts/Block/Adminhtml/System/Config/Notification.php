<?php
    class Veles_Discounts_Block_Adminhtml_System_Config_Notification extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
    {
        public function render(Varien_Data_Form_Element_Abstract $element)
        {
            $helper = Mage::helper('veles_discounts');

            $html = "
                <pre>".print_r($helper->getDiscountMethod(), true)."</pre>
                <hr />
                <pre>".print_r($helper->getDiscountForQuantityOptions(), true)."</pre>
                <hr />
                <pre>".print_r($helper->getDiscountForTotalOptions(), true)."</pre>
            ";

            return $html;
        }
    }
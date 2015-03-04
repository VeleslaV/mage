<?php
    class Veles_Discounts_Block_Sales_Order_Discount extends Mage_Core_Block_Template
    {
        public function getOrder()
        {
            return $this->getParentBlock()->getOrder();
        }

        public function getSource()
        {
            return $this->getParentBlock()->getSource();
        }

        public function initTotals()
        {
            if ((float) $this->getOrder()->getBaseCreditAmount()) {
                $source = $this->getSource();
                $value  = $source->getCreditAmount();

                $this->getParentBlock()->addTotal(new Varien_Object(array(
                    'code'   => 'discount',
                    'strong' => false,
                    'label'  => Mage::helper('veles_discounts')->__('Discount'),
                    'value'  => $value
                )));
            }

            return $this;
        }
    }
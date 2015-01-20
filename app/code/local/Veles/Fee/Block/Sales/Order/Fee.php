<?php
    class Veles_Fee_Block_Sales_Order_Fee extends Mage_Core_Block_Template
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
            if ((float) $this->getOrder()->getBaseFeeAmount()) {
                $source = $this->getSource();
                $value  = $source->getFeeAmount();

                $this->getParentBlock()->addTotal(new Varien_Object(array(
                    'code'   => 'fee',
                    'strong' => false,
                    'label'  => Mage::helper('fee')->__('Fee'),
                    'value'  => $value
                )));
            }

            return $this;
        }
    }
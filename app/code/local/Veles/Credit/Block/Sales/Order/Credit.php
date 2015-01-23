<?php
    class Veles_Credit_Block_Sales_Order_Credit extends Mage_Core_Block_Template
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
                    'code'   => 'credit',
                    'strong' => false,
                    'label'  => Mage::helper('credit')->__('Credit'),
                    'value'  => $value
                )));
            }

            return $this;
        }
    }
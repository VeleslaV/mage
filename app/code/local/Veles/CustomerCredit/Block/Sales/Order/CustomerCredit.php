<?php
    class Veles_CustomerCredit_Block_Sales_Order_CustomerCredit extends Mage_Core_Block_Template
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
            if ((float) $this->getOrder()->getBaseCustomerCreditAmount()) {
                $source = $this->getSource();
                $value  = $source->getCustomerCreditAmount();

                $this->getParentBlock()->addTotal(new Varien_Object(array(
                    'code'   => 'customercredit',
                    'strong' => false,
                    'label'  => Mage::helper('customercredit')->__('Customer Credit'),
                    'value'  => $value
                )));
            }

            return $this;
        }
    }
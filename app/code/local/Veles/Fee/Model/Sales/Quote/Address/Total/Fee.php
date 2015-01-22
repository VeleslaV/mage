<?php
    class Veles_Fee_Model_Sales_Quote_Address_Total_Fee extends Mage_Sales_Model_Quote_Address_Total_Abstract
    {
        protected $_code = 'fee';

        public function collect(Mage_Sales_Model_Quote_Address $address)
        {
            parent::collect($address);

            $this->_setAmount(0);
            $this->_setBaseAmount(0);

            $items = $this->_getAddressItems($address);
            if (!count($items)) {
                return $this;
            }

            if (Veles_Fee_Model_Fee::canApply()) {
                $fee = Veles_Fee_Model_Fee::getFee();

                $address->setFeeAmount($fee);
                $address->setBaseFeeAmount($fee);

                $address->setGrandTotal($address->getGrandTotal() - $address->getFeeAmount());
                $address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseFeeAmount());
            }

            return $this;
        }

        public function fetch(Mage_Sales_Model_Quote_Address $address)
        {
            $amount = $address->getFeeAmount();

            if($amount>0){
                $address->addTotal(array(
                    'code' => $this->getCode(),
                    'title' => Mage::helper('fee')->__('Fee'),
                    'value' => $amount
                ));
            }

            return $this;
        }
    }
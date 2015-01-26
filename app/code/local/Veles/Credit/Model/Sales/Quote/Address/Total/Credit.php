<?php
    class Veles_Credit_Model_Sales_Quote_Address_Total_Credit extends Mage_Sales_Model_Quote_Address_Total_Abstract
    {
        protected $_code = 'credit';

        public function collect(Mage_Sales_Model_Quote_Address $address)
        {
            parent::collect($address);

            $this->_setAmount(0);
            $this->_setBaseAmount(0);

            $items = $this->_getAddressItems($address);
            if (!count($items)) {
                return $this;
            }

            if (Veles_Credit_Model_Credit::canApply()) {
                $credit = Veles_Credit_Model_Credit::getCredit();

                $address->setCreditAmount($credit);
                $address->setBaseCreditAmount($credit);

                $address->setGrandTotal($address->getGrandTotal() - $address->getCreditAmount());
                $address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseCreditAmount());
            }

            return $this;
        }

        public function fetch(Mage_Sales_Model_Quote_Address $address)
        {
            $amount = $address->getCreditAmount();

            if($amount>0){
                $address->addTotal(array(
                    'code' => $this->getCode(),
                    'title' => Mage::helper('credit')->__('Credit'),
                    'value' => $amount
                ));
            }

            return $this;
        }
    }
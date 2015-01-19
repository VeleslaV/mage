<?php
    class Veles_CustomerCredit_Model_Sales_Quote_Address_Total_CustomerCredit extends Mage_Sales_Model_Quote_Address_Total_Abstract
    {
        protected $_code = 'customercredit';

        public function collect(Mage_Sales_Model_Quote_Address $address)
        {
            parent::collect($address);

            $this->_setAmount(0);
            $this->_setBaseAmount(0);

            $items = $this->_getAddressItems($address);
            if (!count($items)) {
                return $this;
            }

            $quote = $address->getQuote();

            if (Veles_CustomerCredit_Model_CustomerCredit::canApply($address)) {
                $exist_amount = $quote->getCustomerCreditAmount();
                $customercredit = Veles_CustomerCredit_Model_CustomerCredit::getCustomerCredit();
                $balance = $customercredit - $exist_amount;

                $address->setCustomerCreditAmount($balance);
                $address->setBaseCustomerCreditAmount($balance);

                $quote->setCustomerCreditAmount($balance);

                $address->setGrandTotal($address->getGrandTotal() + $address->getCustomerCreditAmount());
                $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseCustomerCreditAmount());
            }

            return $this;
        }

        public function fetch(Mage_Sales_Model_Quote_Address $address)
        {
            $amount = $address->getCustomerCreditAmount();
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('customercredit')->__('Customer Credit'),
                'value' => $amount
            ));
            return $this;
        }
    }
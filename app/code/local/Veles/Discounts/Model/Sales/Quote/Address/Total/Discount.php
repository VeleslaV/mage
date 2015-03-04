<?php
    class Veles_Discounts_Model_Sales_Quote_Address_Total_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract
    {
        protected $_code = 'discount';

        public function collect(Mage_Sales_Model_Quote_Address $address)
        {
            parent::collect($address);

            $this->_setAmount(0);
            $this->_setBaseAmount(0);
            $discountModel = Mage::getModel('veles_discounts/discount');

            $items = $this->_getAddressItems($address);
            if (!count($items)) {
                return $this;
            }

            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $customerId = $customerData->getId();

                if($discountModel->canApply($customerId)) {
                    $helper = Mage::helper('veles_discounts');

                    $discountPercent = $discountModel->getCustomerDiscountPercent($customerId);
                    $discountAmount = $helper->getDiscountAmount($address->getSubtotal(), $discountPercent);

                    $address->setDiscountAmount($discountAmount);
                    $address->setBaseDiscountAmount($discountAmount);

                    $address->setGrandTotal($address->getGrandTotal() + $discountAmount);
                    $address->setBaseGrandTotal($address->getBaseGrandTotal() + $discountAmount);
                }
            }

            return $this;
        }

        public function fetch(Mage_Sales_Model_Quote_Address $address)
        {
            $amount = $address->getDiscountAmount() * -1;

            if($amount>0){
                $address->addTotal(array(
                    'code' => $this->getCode(),
                    'title' => Mage::helper('veles_discounts')->__('Discount'),
                    'value' => "-".$amount
                ));
            }

            return $this;
        }
    }
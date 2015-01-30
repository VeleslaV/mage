<?php
    class Veles_Discounts_Model_Observer
    {
//        public function orderPlaceAfter(Varien_Event_Observer $observer)
//        {
            /** TD: create line in a table **/
//            $order = $observer->getEvent()->getOrder();
//            $orderCustomerId = $order->getData("customer_id");
//
//            if ($order->getData("customer_id")){
//                $checkCreditModel = Mage::getModel('credit/credit');
//                $checkCreditModel->load($orderCustomerId);
//                $creditCustomer = $checkCreditModel->getData('customer_id');
//
//                if(empty($creditCustomer)){
//                    $checkCreditModel->setData('customer_id', $orderCustomerId);
//                    $checkCreditModel->save();
//                }
//
//                $newCreditModel = Mage::getModel('credit/credit');
//                if (Veles_Credit_Model_Credit::canApply()) {
//                    $newCreditModel->load($orderCustomerId);
//                    $resultCreditAmount = $newCreditModel->getData("credit_amount") - Veles_Credit_Model_Credit::getCredit();
//
//                    $newCreditModel->setData('credit_amount', $resultCreditAmount);
//                    $newCreditModel->save();
//                }
//            }
//
//            return $this;
//        }

        public function invoiceSaveAfter(Varien_Event_Observer $observer)
        {
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();
            $orderCustomerId = $order->getData("customer_id");

            $discountModel = Mage::getModel('veles_discounts/discount');
            $discountModel->load($orderCustomerId);

            $customerDiscountLevel = $discountModel->getDiscountLevel();
            $customerDiscountNextLevel = $customerDiscountLevel + 1;

            $customerOrdersResultQuantity = $discountModel->getCustomerOrdersQuantity() + 1;                        /* customer new quantity of all orders */
            $customerOrdersResultValue = $discountModel->getCustomerOrdersValue() + $order->getBaseGrandTotal();    /* customer new total value of all orders */

            $helper = Mage::helper('veles_discounts');
            $discountMethod = $helper->getDiscountMethod();

            if($discountMethod == "for_quantity"){
                $discountData = $helper->getDiscountForQuantityData();
                $nextLevelCheckValue = $customerOrdersResultQuantity;       /* value for check advance to the next level, depending on the rules */
            }else{
                $discountData = $helper->getDiscountForTotalData();
                $nextLevelCheckValue = $customerOrdersResultValue;          /* value for check advance to the next level, depending on the rules */
            }

            if(isset($discountData[$customerDiscountNextLevel])){                                       /* if isset next level */
                $nextLevelActivateOn = $discountData[$customerDiscountNextLevel]['level_activate_on'];  /* rule for activate next level */
                if($nextLevelCheckValue >= $nextLevelActivateOn){                                       /* if activation rule is true */
                    $discountModel->setDiscountLevel($customerDiscountNextLevel);                       /* set new customer level */

                    /**
                     * should generate a new coupon code and send a letter about new level
                     **/
                }
            }

            $discountModel->setCustomerOrdersQuantity($customerOrdersResultQuantity);
            $discountModel->setCustomerOrdersValue($customerOrdersResultValue);
            $discountModel->save();

            if ($invoice->getBaseDiscountAmount()) {
                $order->setDiscountInvoiced($order->setDiscountInvoiced() + $invoice->getDiscountAmount());
                $order->setBaseDiscountInvoiced($order->setBaseDiscountInvoiced() + $invoice->getBaseDiscountAmount());
            }

            return $this;
        }

        public function creditmemoSaveAfter(Varien_Event_Observer $observer)
        {
            /** TD:  **/
//            $creditmemo = $observer->getEvent()->getCreditmemo();
//            $order = $creditmemo->getOrder();
//
//            if ($creditmemo->getCreditAmount()) {
//                $order->setCreditAmountRefunded($order->getCreditAmountRefunded() + $creditmemo->getCreditAmount());
//                $order->setBaseCreditAmountRefunded($order->getBaseCreditAmountRefunded() + $creditmemo->getBaseCreditAmount());
//            }
//
//            $creditModel = Mage::getModel('credit/credit');
//            $creditModel->load($order->getData("customer_id"));
//
//            $addCreditAmount = $order->getBaseCreditAmountGranted();
//            $resultCreditAmount = ($creditModel->getData("credit_amount") - $addCreditAmount) + $creditmemo->getBaseCreditAmount();
//
//            $creditModel->setData('credit_amount', $resultCreditAmount);
//            $creditModel->save();
//
//            return $this;
        }
    }
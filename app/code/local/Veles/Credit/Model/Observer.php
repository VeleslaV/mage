<?php
    class Veles_Credit_Model_Observer
    {
        public function orderPlaceAfter(Varien_Event_Observer $observer)
        {
            $order = $observer->getEvent()->getOrder();
            $orderCustomerId = $order->getData("customer_id");

            if ($order->getData("customer_id")){
                $checkCreditModel = Mage::getModel('credit/credit');
                $checkCreditModel->load($orderCustomerId);
                $creditCustomer = $checkCreditModel->getData('customer_id');

                if(empty($creditCustomer)){
                    $checkCreditModel->setData('customer_id', $orderCustomerId);
                    $checkCreditModel->save();
                }

                $newCreditModel = Mage::getModel('credit/credit');
                if (Veles_Credit_Model_Credit::canApply()) {
                    $newCreditModel->load($orderCustomerId);
                    $resultCreditAmount = $newCreditModel->getData("credit_amount") - Veles_Credit_Model_Credit::getCredit();

                    $newCreditModel->setData('credit_amount', $resultCreditAmount);
                    $newCreditModel->save();
                }
            }

            return $this;
        }

        public function invoiceSaveAfter(Varien_Event_Observer $observer)
        {
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();
            $orderCustomerId = $order->getData("customer_id");

            $creditModel = Mage::getModel('credit/credit');
            $creditModel->load($orderCustomerId);

            $addCreditRule = Mage::getStoreConfig('credit_options/rules/credit_method_rule');
            if($addCreditRule == "static"){
                $addCreditAmount = round(Mage::getStoreConfig('credit_options/rules/credit_static_rule'), 2);
            }else{
                $addCreditPercentage = Mage::getStoreConfig('credit_options/rules/credit_percentage_rule');
                $addCreditAmount = round(($order->getBaseGrandTotal() / 100) * $addCreditPercentage, 2);
            }
            $resultCreditAmount = $creditModel->getData("credit_amount") + $addCreditAmount;

            $creditModel->setData('credit_amount', $resultCreditAmount);
            $creditModel->save();

            if ($invoice->getBaseCreditAmount()) {
                $order->setCreditAmountInvoiced($order->getCreditAmountInvoiced() + $invoice->getCreditAmount());
                $order->setBaseCreditAmountInvoiced($order->getBaseCreditAmountInvoiced() + $invoice->getBaseCreditAmount());
            }

            $order->setCreditAmountGranted($addCreditAmount);
            $order->setBaseCreditAmountGranted($addCreditAmount);

            return $this;
        }

        public function creditmemoSaveAfter(Varien_Event_Observer $observer)
        {
            $creditmemo = $observer->getEvent()->getCreditmemo();
            $order = $creditmemo->getOrder();

            if ($creditmemo->getCreditAmount()) {
                $order->setCreditAmountRefunded($order->getCreditAmountRefunded() + $creditmemo->getCreditAmount());
                $order->setBaseCreditAmountRefunded($order->getBaseCreditAmountRefunded() + $creditmemo->getBaseCreditAmount());
            }

            $creditModel = Mage::getModel('credit/credit');
            $creditModel->load($order->getData("customer_id"));

            $addCreditAmount = $order->getBaseCreditAmountGranted();
            $resultCreditAmount = ($creditModel->getData("credit_amount") - $addCreditAmount) + $creditmemo->getBaseCreditAmount();

            $creditModel->setData('credit_amount', $resultCreditAmount);
            $creditModel->save();

            return $this;
        }

        public function updatePaypalTotal(Varien_Event_Observer $observer)
        {
            $cart = $observer->getEvent()->getPaypalCart();

            $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getCreditAmount());

            return $this;
        }
    }
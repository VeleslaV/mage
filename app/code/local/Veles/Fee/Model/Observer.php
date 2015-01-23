<?php
    class Veles_Fee_Model_Observer
    {
        public function orderPlaceAfter(Varien_Event_Observer $observer)
        {
            if (Veles_Fee_Model_Fee::canApply()) {
                $order = $observer->getEvent()->getOrder();

                if ($order->getData("customer_id")) {
                    $feeModel = Mage::getModel('fee/fee');
                    $feeModel->load($order->getData("customer_id"));
                    $resultFeeAmount = $feeModel->getData("credit_amount") - Veles_Fee_Model_Fee::getFee();

                    $feeModel->setData('credit_amount', $resultFeeAmount);
                    $feeModel->save();
                }
            }

            return $this;
        }

        public function invoiceSaveAfter(Varien_Event_Observer $observer)
        {
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();

            $feeModel = Mage::getModel('fee/fee');
            $feeModel->load($order->getData("customer_id"));
            $addFeeRule = Mage::getStoreConfig('fee_options/rules/fee_method_rule');

            if($addFeeRule == "static"){
                $addFeeAmount = round(Mage::getStoreConfig('fee_options/rules/fee_static_rule'), 2);
            }else{
                $addFeePercentage = Mage::getStoreConfig('fee_options/rules/fee_percentage_rule');
                $addFeeAmount = round(($order->getBaseGrandTotal() / 100) * $addFeePercentage, 2);
            }
            $resultFeeAmount = $feeModel->getData("credit_amount") + $addFeeAmount;

            $feeModel->setData('credit_amount', $resultFeeAmount);
            $feeModel->save();

            if ($invoice->getBaseFeeAmount()) {
                $order->setFeeAmountInvoiced($order->getFeeAmountInvoiced() + $invoice->getFeeAmount());
                $order->setBaseFeeAmountInvoiced($order->getBaseFeeAmountInvoiced() + $invoice->getBaseFeeAmount());
            }

            $order->setFeeAmountGranted($addFeeAmount);
            $order->setBaseFeeAmountGranted($addFeeAmount);

            return $this;
        }

        public function creditmemoSaveAfter(Varien_Event_Observer $observer)
        {
            $creditmemo = $observer->getEvent()->getCreditmemo();
            $order = $creditmemo->getOrder();

            if ($creditmemo->getFeeAmount()) {
                $order->setFeeAmountRefunded($order->getFeeAmountRefunded() + $creditmemo->getFeeAmount());
                $order->setBaseFeeAmountRefunded($order->getBaseFeeAmountRefunded() + $creditmemo->getBaseFeeAmount());
            }

            $feeModel = Mage::getModel('fee/fee');
            $feeModel->load($order->getData("customer_id"));

            $addFeeAmount = $order->getBaseFeeAmountGranted();
            $resultFeeAmount = ($feeModel->getData("credit_amount") - $addFeeAmount) + $creditmemo->getBaseFeeAmount();

            $feeModel->setData('credit_amount', $resultFeeAmount);
            $feeModel->save();

            return $this;
        }

        public function updatePaypalTotal(Varien_Event_Observer $observer)
        {
            $cart = $observer->getEvent()->getPaypalCart();

            $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getFeeAmount());

            return $this;
        }
    }
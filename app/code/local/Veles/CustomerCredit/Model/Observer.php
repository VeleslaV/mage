<?php
    class Veles_CustomerCredit_Model_Observer
    {
        public function invoiceSaveAfter(Varien_Event_Observer $observer)
        {
            $invoice = $observer->getEvent()->getInvoice();

            if ($invoice->getBaseCustomerCreditAmount()) {
                $order = $invoice->getOrder();
                $order->setCustomerCreditAmountInvoiced($order->getCustomerCreditAmountInvoiced() + $invoice->getCustomerCreditAmount());
                $order->setBaseCustomerCreditAmountInvoiced($order->getBaseCustomerCreditAmountInvoiced() + $invoice->getBaseCustomerCreditAmount());
            }

            return $this;
        }

        public function creditmemoSaveAfter(Varien_Event_Observer $observer)
        {
            $creditmemo = $observer->getEvent()->getCreditmemo();

            if ($creditmemo->getCustomerCreditAmount()) {
                $order = $creditmemo->getOrder();
                $order->setCustomerCreditAmountRefunded($order->getCustomerCreditAmountRefunded() + $creditmemo->getCustomerCreditAmount());
                $order->setBaseCustomerCreditAmountRefunded($order->getBaseCustomerCreditAmountRefunded() + $creditmemo->getBaseCustomerCreditAmount());
            }

            return $this;
        }

        public function updatePaypalTotal(Varien_Event_Observer $observer)
        {
            $cart = $observer->getEvent()->getPaypalCart();

            $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getCustomerCreditAmount());

            return $this;
        }
    }
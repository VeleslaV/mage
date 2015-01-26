<?php
    class Veles_Credit_Model_Sales_Order_Total_Invoice_Credit extends Mage_Sales_Model_Order_Invoice_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Invoice $invoice)
        {
            $order = $invoice->getOrder();

            $creditAmountLeft = $order->getCreditAmount() - $order->getCreditAmountInvoiced();
            $baseCreditAmountLeft = $order->getBaseCreditAmount() - $order->getBaseCreditAmountInvoiced();

            if (abs($baseCreditAmountLeft) < $invoice->getBaseGrandTotal()) {
                $invoice->setGrandTotal($invoice->getGrandTotal() - $creditAmountLeft);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseCreditAmountLeft);
            } else {
                $creditAmountLeft = $invoice->getGrandTotal() * -1;
                $baseCreditAmountLeft = $invoice->getBaseGrandTotal() * -1;

                $invoice->setGrandTotal(0);
                $invoice->setBaseGrandTotal(0);
            }

            $invoice->setCreditAmount($creditAmountLeft);
            $invoice->setBaseCreditAmount($baseCreditAmountLeft);

            return $this;
        }
    }

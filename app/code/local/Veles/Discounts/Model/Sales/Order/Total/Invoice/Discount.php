<?php
    class Veles_Discounts_Model_Sales_Order_Total_Invoice_Discount extends Mage_Sales_Model_Order_Invoice_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Invoice $invoice)
        {
            $order = $invoice->getOrder();

            $discountAmountLeft = $order->getDiscountAmount() - $order->getDiscountInvoiced();
            $baseDiscountAmountLeft = $order->getBaseDiscountAmount() - $order->getBaseDiscountInvoiced();

            if (abs($baseDiscountAmountLeft) < $invoice->getBaseGrandTotal()) {
                $invoice->setGrandTotal($invoice->getGrandTotal() + $discountAmountLeft);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseDiscountAmountLeft);
            } else {
                $discountAmountLeft = $invoice->getGrandTotal() * -1;
                $baseDiscountAmountLeft = $invoice->getBaseGrandTotal() * -1;

                $invoice->setGrandTotal(0);
                $invoice->setBaseGrandTotal(0);
            }

            $invoice->setDiscountAmount($discountAmountLeft);
            $invoice->setBaseDiscountAmount($baseDiscountAmountLeft);

            return $this;
        }
    }

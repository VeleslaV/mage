<?php
    class Veles_CustomerCredit_Model_Sales_Order_Total_Invoice_CustomerCredit extends Mage_Sales_Model_Order_Invoice_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Invoice $invoice)
        {
            $order = $invoice->getOrder();
    
            $customerCreditAmountLeft = $order->getCustomerCreditAmount() - $order->getCustomerCreditAmountInvoiced();
            $baseCustomerCreditAmountLeft = $order->getBaseCustomerCreditAmount() - $order->getBaseCustomerCreditAmountInvoiced();
    
            if (abs($baseCustomerCreditAmountLeft) < $invoice->getBaseGrandTotal()) {
                $invoice->setGrandTotal($invoice->getGrandTotal() + $customerCreditAmountLeft);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseCustomerCreditAmountLeft);
            } else {
                $customerCreditAmountLeft = $invoice->getGrandTotal() * -1;
                $baseCustomerCreditAmountLeft = $invoice->getBaseGrandTotal() * -1;
    
                $invoice->setGrandTotal(0);
                $invoice->setBaseGrandTotal(0);
            }
    
            $invoice->setCustomerCreditAmount($customerCreditAmountLeft);
            $invoice->setBaseCustomerCreditAmount($baseCustomerCreditAmountLeft);

            return $this;
        }
    }

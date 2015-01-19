<?php
    class Veles_CustomerCredit_Model_Sales_Order_Total_Creditmemo_Credit extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
        {
            $order = $creditmemo->getOrder();
    
            if($order->getCustomerCreditAmountInvoiced() > 0) {
    
                $customerCreditAmountLeft = $order->getCustomerCreditAmountInvoiced() - $order->getCustomerCreditAmountRefunded();
                $baseCustomerCreditAmountLeft = $order->getBaseCustomerCreditAmountInvoiced() - $order->getBaseCustomerCreditAmountRefunded();
    
                if ($baseCustomerCreditAmountLeft > 0) {
                    $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $customerCreditAmountLeft);
                    $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseCustomerCreditAmountLeft);
                    $creditmemo->setCustomerCreditAmount($customerCreditAmountLeft);
                    $creditmemo->setBaseCustomerCreditAmount($baseCustomerCreditAmountLeft);
                }
    
            } else {
    
                $customerCreditAmount = $order->getCustomerCreditAmountInvoiced();
                $baseCustomerCreditAmount = $order->getBaseCustomerCreditAmountInvoiced();
    
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $customerCreditAmount);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseCustomerCreditAmount);
                $creditmemo->setCustomerCreditAmount($customerCreditAmount);
                $creditmemo->setBaseCustomerCreditAmount($baseCustomerCreditAmount);
    
            }
    
            return $this;
        }
    }

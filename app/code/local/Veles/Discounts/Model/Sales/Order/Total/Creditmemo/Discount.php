<?php
    class Veles_Discounts_Model_Sales_Order_Total_Creditmemo_Discount extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
        {
//            $order = $creditmemo->getOrder();
//
//            if($order->getCreditAmountInvoiced() > 0) {
//
//                $creditAmountLeft = $order->getCreditAmountInvoiced() - $order->getCreditAmountRefunded();
//                $baseCreditAmountLeft = $order->getBaseCreditAmountInvoiced() - $order->getBaseCreditAmountRefunded();
//
//                if ($baseCreditAmountLeft > 0) {
//                    $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $creditAmountLeft);
//                    $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseCreditAmountLeft);
//                    $creditmemo->setCreditAmount($creditAmountLeft);
//                    $creditmemo->setBaseCreditAmount($baseCreditAmountLeft);
//                }
//
//            } else {
//
//                $creditAmount = $order->getCreditAmountInvoiced();
//                $baseCreditAmount = $order->getBaseCreditAmountInvoiced();
//
//                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $creditAmount);
//                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseCreditAmount);
//                $creditmemo->setCreditAmount($creditAmount);
//                $creditmemo->setBaseCreditAmount($baseCreditAmount);
//
//            }

            return $this;
        }
    }

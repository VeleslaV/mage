<?php
    class Veles_Discounts_Model_Sales_Order_Total_Creditmemo_Discount extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
    {
        public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
        {
            $order = $creditmemo->getOrder();

            if(abs($order->getDiscountInvoiced()) > 0) {

                $discountAmountLeft = $order->getDiscountInvoiced() - $order->getDiscountRefunded();
                $baseDiscountAmountLeft = $order->getBaseDiscountInvoiced() - $order->getBaseDiscountRefunded();

                if (abs($baseDiscountAmountLeft) > 0) {
                    $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $discountAmountLeft);
                    $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseDiscountAmountLeft);
                    $creditmemo->setDiscountAmount($discountAmountLeft);
                    $creditmemo->setBaseDiscountAmount($baseDiscountAmountLeft);
                }

            } else {

                $discountAmount = $order->getDiscountInvoiced();
                $baseDiscountAmount = $order->getBaseDiscountInvoiced();

                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $discountAmount);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseDiscountAmount);
                $creditmemo->setDiscountAmount($discountAmount);
                $creditmemo->setBaseDiscountAmount($baseDiscountAmount);

            }

            return $this;
        }
    }

<?php
    class Veles_Discounts_Model_Observer
    {
        public function orderPlaceAfter(Varien_Event_Observer $observer)
        {
            $order = $observer->getEvent()->getOrder();
            $orderCustomerId = $order->getData("customer_id");

            if (!empty($orderCustomerId) AND $orderCustomerId !== 0){
                $checkDiscountModel = Mage::getModel('veles_discounts/discount');
                $checkDiscountModel->load($orderCustomerId);
                $discountCustomer = $checkDiscountModel->getData('customer_id');

                if(empty($discountCustomer)){
                    $checkDiscountModel->setData('customer_id', $orderCustomerId);
                    $checkDiscountModel->save();
                }
            }
        }

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
                    /* customer have new level. generate new coupon code and send an email.  */

                    $newCouponCode = $orderCustomerId."+".$order->getData("customer_email")."+".time()."+5ALT";
                    $newCouponCode = md5($newCouponCode);
                    $newCouponCode = strtoupper($newCouponCode);
                    $newCouponCode = substr($newCouponCode, 0, 15);

                    $discountModel->setCustomerDiscountCoupon($newCouponCode);                          /* set new customer coupon */

                    /**
                     * should send a letter about new level
                     **/
                }
            }

            $discountModel->setCustomerOrdersQuantity($customerOrdersResultQuantity);
            $discountModel->setCustomerOrdersValue($customerOrdersResultValue);
            $discountModel->save();

            return $this;
        }

        public function creditmemoSaveAfter(Varien_Event_Observer $observer)
        {
            $creditmemo = $observer->getEvent()->getCreditmemo();
            $order = $creditmemo->getOrder();
            $orderCustomerId = $order->getData("customer_id");

            $discountModel = Mage::getModel('veles_discounts/discount');
            $discountModel->load($orderCustomerId);
            $helper = Mage::helper('veles_discounts');

            $customerDiscountLevel = $discountModel->getDiscountLevel();
            $customerDiscountPreviousLevel = $customerDiscountLevel - 1;

            $customerOrdersResultQuantity = $discountModel->getCustomerOrdersQuantity() - 1;                        /* customer new quantity of all orders */
            $customerOrdersResultValue = $discountModel->getCustomerOrdersValue() - $order->getBaseGrandTotal();    /* customer new total value of all orders */

            $discountMethod = $helper->getDiscountMethod();
            if($discountMethod == "for_quantity"){
                $discountData = $helper->getDiscountForQuantityData();
                $levelCheckValue = $customerOrdersResultQuantity;       /* value for check advance to the next level, depending on the rules */
            }else{
                $discountData = $helper->getDiscountForTotalData();
                $levelCheckValue = $customerOrdersResultValue;          /* value for check advance to the next level, depending on the rules */
            }

            $currentLevelActivateOn = $discountData[$customerDiscountLevel]['level_activate_on'];   /* rule for current level */
            if($levelCheckValue < $currentLevelActivateOn){                                         /* if activation rule for current level is false */
                /* reduce customer level. generate new coupon code and send an email. */

                $newCouponCode = $orderCustomerId."+".$order->getData("customer_email")."+".time()."+5ALT";
                $newCouponCode = md5($newCouponCode);
                $newCouponCode = strtoupper($newCouponCode);
                $newCouponCode = substr($newCouponCode, 0, 15);

                $discountModel->setCustomerDiscountCoupon($newCouponCode);                          /* set new customer coupon */

                /**
                 * should send a letter about reduced level
                 **/
            }

            $discountModel->setCustomerOrdersQuantity($customerOrdersResultQuantity);
            $discountModel->setCustomerOrdersValue($customerOrdersResultValue);
            $discountModel->save();

            return $this;
        }
    }
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
            $orderCustomerEmail = $order->getData("customer_email");

            $discountModel = Mage::getModel('veles_discounts/discount')->load($orderCustomerId);

            $customerOrdersResultQuantity = $discountModel->getCustomerOrdersQuantity() + 1;                        /* customer new quantity of all orders */
            $customerOrdersResultValue = $discountModel->getCustomerOrdersValue() + $order->getBaseGrandTotal();    /* customer new total value of all orders */

            $helper = Mage::helper('veles_discounts');
            $discountMethod = $helper->getDiscountMethod();

            if($discountMethod == "for_quantity"){
                $discountData = $helper->getDiscountForQuantityData();
                $newLevelCheckValue = $customerOrdersResultQuantity;                   /* value for check advance to the new level, depending on the rules */
                $currentLevelCheckValue = $discountModel->getCustomerOrdersQuantity();  /* value for check current discount level */
            }else{
                $discountData = $helper->getDiscountForTotalData();
                $newLevelCheckValue = $customerOrdersResultValue;                      /* value for check advance to the new level, depending on the rules */
                $currentLevelCheckValue = $discountModel->getCustomerOrdersValue();     /* value for check current discount level */
            }

            $customerDiscountLevel = $discountModel->getDiscountLevelByAmount($currentLevelCheckValue);
            $customerDiscountNewLevel = $discountModel->getDiscountLevelByAmount($newLevelCheckValue);

            if(($customerDiscountLevel !== $customerDiscountNewLevel) AND (isset($discountData[$customerDiscountNewLevel]))){                                       /* if isset next level */
                /* customer have new level. generate new coupon code and send an email.  */

                $newCouponCode = $orderCustomerId."+".$orderCustomerEmail."+".time()."+5ALT";
                $newCouponCode = md5($newCouponCode);
                $newCouponCode = strtoupper($newCouponCode);
                $newCouponCode = substr($newCouponCode, 0, 15);

                $discountModel->setCustomerDiscountCoupon($newCouponCode);              /* set new customer coupon */

                $helper->sendEmailNotification($orderCustomerEmail, $newCouponCode);    /* send a letter about new level */
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
            $orderCustomerEmail = $order->getData("customer_email");

            $discountModel = Mage::getModel('veles_discounts/discount')->load($orderCustomerId);
            $helper = Mage::helper('veles_discounts');

            $customerOrdersResultQuantity = $discountModel->getCustomerOrdersQuantity() - 1;                        /* customer new quantity of all orders */
            $customerOrdersResultValue = $discountModel->getCustomerOrdersValue() - $order->getBaseGrandTotal();    /* customer new total value of all orders */

            $discountMethod = $helper->getDiscountMethod();
            if($discountMethod == "for_quantity"){
                $discountData = $helper->getDiscountForQuantityData();
                $newLevelCheckValue = $customerOrdersResultQuantity;       /* value for check advance to the new level, depending on the rules */
                $currentLevelCheckValue = $discountModel->getCustomerOrdersQuantity();
            }else{
                $discountData = $helper->getDiscountForTotalData();
                $newLevelCheckValue = $customerOrdersResultValue;          /* value for check advance to the new level, depending on the rules */
                $currentLevelCheckValue = $discountModel->getCustomerOrdersValue();
            }

            $customerDiscountLevel = $discountModel->getDiscountLevelByAmount($currentLevelCheckValue);
            $customerDiscountNewLevel = $discountModel->getDiscountLevelByAmount($newLevelCheckValue);

            if(($customerDiscountLevel !== $customerDiscountNewLevel) AND (isset($discountData[$customerDiscountNewLevel]))){                                           /* if activation rule for current level is false */
                /* reduce customer level. generate new coupon code and send an email. */

                $newCouponCode = $orderCustomerId."+".$orderCustomerEmail."+".time()."+5ALT";
                $newCouponCode = md5($newCouponCode);
                $newCouponCode = strtoupper($newCouponCode);
                $newCouponCode = substr($newCouponCode, 0, 15);

                $discountModel->setCustomerDiscountCoupon($newCouponCode);                          /* set new customer coupon */

                $helper->sendEmailNotification($orderCustomerEmail, $newCouponCode);                /* send a letter about new level */
            }

            $discountModel->setCustomerOrdersQuantity($customerOrdersResultQuantity);
            $discountModel->setCustomerOrdersValue($customerOrdersResultValue);
            $discountModel->save();

            return $this;
        }
    }
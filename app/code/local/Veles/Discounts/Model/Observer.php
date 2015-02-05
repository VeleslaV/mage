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
            $helper = Mage::helper('veles_discounts');

            $ordersResultQuantity = $discountModel->getCustomerOrdersQuantity() + 1;                                    /* customer new quantity of all orders */
            $ordersResultValue = $discountModel->getCustomerOrdersValue() + $invoice->getBaseGrandTotal();              /* customer new total value of all orders */

            $newLevelCondition = $helper->getDiscountConditionValue($ordersResultQuantity, $ordersResultValue);
            $currentLevelCondition = $helper->getDiscountConditionValue($discountModel->getCustomerOrdersQuantity(), $discountModel->getCustomerOrdersValue());
            $discountData = $helper->getCurrentDiscountData();

            $customerLevel = $helper->getDiscountLevelByAmount($currentLevelCondition);
            $customerNewLevel = $helper->getDiscountLevelByAmount($newLevelCondition);

            if(($customerLevel !== $customerNewLevel) AND (isset($discountData[$customerNewLevel]))){                   /* if isset new level */
                $helper->sendEmailNotification($orderCustomerEmail);                                                    /* send a letter about new level */
            }

            $discountModel->setCustomerOrdersQuantity($ordersResultQuantity);
            $discountModel->setCustomerOrdersValue($ordersResultValue);
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

            $ordersResultQuantity = $discountModel->getCustomerOrdersQuantity() - 1;                                    /* customer new quantity of all orders */
            $ordersResultValue = $discountModel->getCustomerOrdersValue() - $creditmemo->getBaseGrandTotal();           /* customer new total value of all orders */

            $newLevelCondition = $helper->getDiscountConditionValue($ordersResultQuantity, $ordersResultValue);
            $currentLevelCondition = $helper->getDiscountConditionValue($discountModel->getCustomerOrdersQuantity(), $discountModel->getCustomerOrdersValue());
            $discountData = $helper->getCurrentDiscountData();

            $customerLevel = $helper->getDiscountLevelByAmount($currentLevelCondition);
            $customerNewLevel = $helper->getDiscountLevelByAmount($newLevelCondition);

            if(($customerLevel !== $customerNewLevel) AND (isset($discountData[$customerNewLevel]))){
                $helper->sendEmailNotification($orderCustomerEmail);                                                    /* send a letter about new level */
            }

            $discountModel->setCustomerOrdersQuantity($ordersResultQuantity);
            $discountModel->setCustomerOrdersValue($ordersResultValue);
            $discountModel->save();

            return $this;
        }
    }
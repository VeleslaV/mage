<?php
    require_once 'Mage/Checkout/controllers/CartController.php';

    class Veles_Discounts_CartController extends Mage_Checkout_CartController
    {
        const XML_PATH_SIMPLE_EMAIL_TEMPLATE = 'simple/send_email/template';

        public function testMailAction()
        {
            $templateId = Mage::getStoreConfig(self::XML_PATH_SIMPLE_EMAIL_TEMPLATE);
            $sender = array(
                'name' => 'MageStore',
                'email' => 'robot@mage.loc'
            );

            $email = "veleslav.ck@gmail.com";
            $emailName = 'Email Notification';
            $vars = array(
                "account_link"=>"asdasd asda sdasd",
                "coupone_code"=>"dsfsdf"
            );
            $storeId = Mage::app()->getStore()->getId();
            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);

        }

        public function couponPostAction()
        {
            $helper = Mage::helper('veles_discounts');
            $quote = Mage::getSingleton('checkout/cart')->getQuote();
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $discountModel = Mage::getModel('veles_discounts/discount')->load($customerData->getId());

            /* No reason continue with empty shopping cart */
            if (!$this->_getCart()->getQuote()->getItemsCount()) {
                $this->_goBack();
                return;
            }
            $couponCode = (string) $this->getRequest()->getParam('coupon_code');

            if ($this->getRequest()->getParam('remove') == 1) {
                $couponCode = '';
                $customer_discount_percent = 0;
                $customer_discount_amount = 0;
            }else{
                $customer_discount_percent = $discountModel->getCustomerDiscountPercent();
                $customer_discount_amount = $helper->getDiscountAmount($quote->getSubtotal(), $customer_discount_percent);
            }
            $oldCouponCode = $quote->getVdCouponCode();

            if (!strlen($couponCode) && !strlen($oldCouponCode)) {
                $this->_goBack();
                return;
            }

            try {
                $codeLength = strlen($couponCode);

                $quote->setCouponCode($couponCode);
                $quote->setVdCouponCode($couponCode);
                $quote->setVdDiscountPercent($customer_discount_percent);
                $quote->setVdDiscountAmount($customer_discount_amount);
                $quote->setBaseVdDiscountAmount($customer_discount_amount);
                $quote->save();

                if($codeLength) {
                    if ($couponCode == $discountModel->getCustomerDiscountCoupon()) {
                        $this->_getSession()->addSuccess(
                            $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode))
                        );
                    } else {
                        $quote->setVdDiscountPercent(0);
                        $quote->setVdDiscountAmount(0);
                        $quote->setBaseVdDiscountAmount(0);
                        $quote->save();

                        $this->_getSession()->addError(
                            $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                        );
                    }
                } else {
                    $this->_getSession()->addSuccess($this->__('Coupon code was canceled.'));
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot apply the coupon code.'));
                Mage::logException($e);
            }

            $this->_goBack();
        }
    }
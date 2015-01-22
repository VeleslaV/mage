<?php
    require_once 'Mage/Checkout/controllers/CartController.php';

    class Veles_Fee_CartController extends Mage_Checkout_CartController
    {
        public function creditPostAction()
        {
            $creditCode = round($this->getRequest()->getParam('credit_code'), 2);
            if ($this->getRequest()->getParam('remove') == 1) {
                $creditCode = 0;
            }

            try {
                $quote = Mage::getSingleton('checkout/cart')->getQuote();
                $quote->setFeeAmount($creditCode);
                $quote->setBaseFeeAmount($creditCode);
                $quote->save();

                if (Veles_Fee_Model_Fee::canApply()) {
                    if($creditCode > 0) {
                        $this->_getSession()->addSuccess(
                            $this->__('Your bonuses was applied.', Mage::helper('core')->escapeHtml($creditCode))
                        );
                    }else{
                        $this->_getSession()->addSuccess($this->__('Your bonuses was canceled.'));
                    }
                }else{
                    $quote->setFeeAmount(0);
                    $quote->setBaseFeeAmount(0);
                    $quote->save();

                    $this->_getSession()->addError(
                        $this->__('You cannot use this amount of bonuses!', Mage::helper('core')->escapeHtml($creditCode))
                    );
                }

            } catch (Exception $e) {

                $this->_getSession()->addError($this->__('Cannot apply bonuses.'));
                Mage::logException($e);
            }


            $this->_goBack();
        }
    }
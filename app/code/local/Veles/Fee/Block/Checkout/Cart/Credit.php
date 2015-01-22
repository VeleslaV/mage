<?php
    class Veles_Fee_Block_Checkout_Cart_Credit extends Mage_Core_Block_Template
    {
        protected function _toHtml()
        {
            $creditMess = "Pay with credits: ";
            $this->assign('creditMess', $creditMess);

            return parent::_toHtml();
        }
    }
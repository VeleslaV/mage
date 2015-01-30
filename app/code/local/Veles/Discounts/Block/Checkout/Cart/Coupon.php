<?php
    class Veles_Discounts_Block_Checkout_Cart_Coupon extends Mage_Checkout_Block_Cart_Coupon
    {
        protected function _toHtml()
        {
            $couponMess = $this->__("Apply coupon");
            $this->setCouponMess($couponMess);

            return parent::_toHtml();
        }
    }
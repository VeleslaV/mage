<?php
    class Veles_RecentlySold_Block_Widget extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
    {
        protected function _toHtml()
        {
            $orders_to_display = $this->getData('products_to_display');

            $itemsCollection = Mage::getModel('recentlysold/products')->getRecentlySoldItems($orders_to_display);
            $this->assign('products_list', $itemsCollection);

            return parent::_toHtml();
        }
    }
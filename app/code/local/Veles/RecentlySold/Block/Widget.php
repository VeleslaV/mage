<?php
    class Veles_RecentlySold_Block_Widget extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
    {
        protected function _toHtml()
        {
            $orders_to_display = $this->getData('products_to_display');

            $model = Mage::getModel('recentlysold/products');
            Mage::register('recently_sold_model', $model);

            $itemsCollection = Mage::registry('recently_sold_model')->getRecentlySoldItems($orders_to_display);
            $this->assign('products_list', $itemsCollection);

            return parent::_toHtml();
        }
    }
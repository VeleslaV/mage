<?php
    class Veles_RecentlySold_Model_Products extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('recentlysold/products');
        }

        public function getRecentlySoldItems($products_to_display)
        {
            $ordersCollection = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToSelect('*')
                ->setOrder('main_table.updated_at','desc')
                ->setPage(1, $products_to_display);
            $ordersCollection->getSelect();

            $products = array();
            $pids = array();
            $products_count = 0;

            if(sizeof($ordersCollection)>0)
            {
                foreach($ordersCollection as $order) {
                    $orderId = $order->getData('entity_id');

                    $itemsCollection = Mage::getModel('sales/order_item')->getCollection();
                    $itemsCollection->addAttributeToSelect('*')
                        ->addFieldToFilter('main_table.order_id', array('eq'=>$orderId))
                        ->addFieldToFilter('main_table.parent_item_id', array('null' => true))
                        ->setOrder('main_table.updated_at','desc');
                    $itemsCollection->getSelect();

                    foreach ($itemsCollection as $item) {
                        if($products_count>=$products_to_display){ break; }

                        if(!array_search($item->getData('product_id'), $pids)){
                            $pids[] = $item->getData('product_id');
                            $products_count++;
                        }else{
                            break;
                        }
                    }
                }

                foreach($pids as $productId){
                    $product = Mage::getModel("catalog/product")->load($productId);
                    $products[] = $product;
                }
            }

            return $products;
        }
    }
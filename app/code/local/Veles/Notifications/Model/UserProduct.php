<?php
    class Veles_Notifications_Model_UserProduct extends Mage_Core_Model_Abstract
    {
        public function _construct()
        {
            parent::_construct();
            $this->_init('veles_notifications/userProduct');
        }



        public function saveData($userId, $productId)
        {
            $data = array(
                'user_id' => $userId,
                'product_id' => $productId
            );
            $this->setData($data);

            try {
                $lineId = $this->save()->getId();
                echo "Data successfully inserted. Insert ID: ".$lineId;
            } catch (Exception $e){
                echo $e->getMessage();
            }

            return $this;
        }



        public function calculateCountForProductId($userId, $productId)
        {
            $userProductCollection = $this->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.user_id', array('eq'=>$userId))
                ->addFieldToFilter('main_table.product_id', array('eq'=>$productId));
            $userProductCollection->getSelect();

            $boughtCount = count($userProductCollection);

            return $boughtCount;
        }



        public function removeThisItems($userId, $productId)
        {
            $userProductCollection = $this->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.user_id', array('eq'=>$userId))
                ->addFieldToFilter('main_table.product_id', array('eq'=>$productId));
            $userProductCollection->getSelect();

            foreach ($userProductCollection as $item) {
                $this->setId($item->getData('line_id'))->delete();
            }

            return $this;
        }
    }
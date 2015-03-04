<?php
    class Veles_News_Model_Resource_Category extends Mage_Core_Model_Mysql4_Abstract
    {

        public function _construct()
        {
            $this->_init('velesnews/table_categories', 'category_id');
        }

    }

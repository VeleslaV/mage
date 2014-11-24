<?php
    class Veles_FAQ_Model_Resource_Status extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('velesfaq/table_faq_statuses', 'status_id');
        }
    }
<?php
    class Veles_CustomerCredit_Model_Resource_Credit extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('velescustomercredit/table_customercredit', 'customer_id');
        }
    }
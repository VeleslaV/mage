<?php
    class Veles_FAQ_Model_Resource_Faq extends Mage_Core_Model_Mysql4_Abstract
    {
        public function _construct()
        {
            $this->_init('velesfaq/table_faq', 'question_id');
        }
    }
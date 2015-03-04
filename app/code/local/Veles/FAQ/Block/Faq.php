<?php
    class Veles_FAQ_Block_Faq extends Mage_Core_Block_Template
    {
        public function getFaqCollection($status_id)
        {
            $questionCollection = Mage::getModel('velesfaq/faq')
                ->getCollection()
                ->addFilter('status', $status_id)
                ->setOrder('created', 'DESC');

            return $questionCollection;
        }
    }
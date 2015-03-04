<?php
    class Veles_News_Block_News extends Mage_Core_Block_Template
    {

        public function getNewsCollection()
        {
            $newsCollection = Mage::getModel('velesnews/news')->getCollection();
            $newsCollection->setOrder('created', 'DESC');
            return $newsCollection;
        }

    }

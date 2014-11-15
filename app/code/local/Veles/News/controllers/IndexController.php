<?php

    class Veles_News_IndexController extends Mage_Core_Controller_Front_Action
    {

        public function indexAction()
        {
            $this->loadLayout();
            $this->renderLayout();
        }

        public function testAction()
        {
            $this->loadLayout();
            $this->renderLayout();
        }

        public function showAction()
        {
            $newsId = Mage::app()->getRequest()->getParam('id', 0);
            $news = Mage::getModel('velesnews/news')->load($newsId);

            if ($news->getId() > 0) {
                $this->loadLayout();
                $this->getLayout()->getBlock('news.content')->assign(array(
                    "newsItem" => $news,
                ));
                $this->renderLayout();
            } else {
                $this->_forward('noRoute');
            }
        }

    }
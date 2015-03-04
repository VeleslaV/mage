<?php
    class Veles_FAQ_IndexController extends Mage_Core_Controller_Front_Action
    {
        public function indexAction()
        {
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $this->renderLayout();
        }

        public function viewAction()
        {
            $questionId = Mage::app()->getRequest()->getParam('id', 0);
            $question = Mage::getModel('velesfaq/faq')->load($questionId);

            if ($question->getId() > 0) {
                $this->loadLayout();
                $this->getLayout()->getBlock('faq.question')->assign(array(
                    "questionItem" => $question,
                ));
                $this->renderLayout();
            } else {
                $this->_forward('noRoute');
            }
        }

        public function categoryAction()
        {
            $this->loadLayout();
            $this->renderLayout();
        }

        public function saveAction()
        {
            $post = $this->getRequest()->getPost();
            if ( $post ) {
                try {
                    $postObject = new Varien_Object();
                    $postObject->setData($post);
                    $error = false;

                    if (!Zend_Validate::is(trim($post['user_email']) , 'EmailAddress')) { $error = true; }
                    if (!Zend_Validate::is(trim($post['title']) , 'NotEmpty')) { $error = true; }
                    if (!Zend_Validate::is(trim($post['category']), 'NotEmpty')) { $error = true; }
                    if (!Zend_Validate::is(trim($post['question']), 'NotEmpty')) { $error = true; }
                    if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) { $error = true; }

                    if ($error) {
                        throw new Exception();
                    }
                    try {
                        $newQuestion = Mage::getModel('velesfaq/faq');
                        $newQuestion->setData('user_email', $post['user_email']);
                        $newQuestion->setData('title', $post['title']);
                        $newQuestion->setData('category_id', $post['category']);
                        $newQuestion->setData('question', $post['question']);
                        $newQuestion->setData('created', now());
                        $newQuestion->save();

                        $questionToCq = Mage::getModel('velesfaq/cq');
                        $questionToCq->setData('category_id', $post['category']);
                        $questionToCq->setData('question_id', $newQuestion->getId());
                        $questionToCq->save();
                    } catch (Exception $e) {
                        $this->_getSession()->addError($e->getMessage("error while saving"));
                    }


                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'text/xml')
                        ->setBody('success');
                } catch (Exception $e) {
                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('Content-Type', 'text/xml')
                        ->setBody('error');
                }
            } else {
                $this->_redirect('*/');
            }
        }
    }
<?php
    class Veles_FAQ_Adminhtml_FaqController extends Mage_Adminhtml_Controller_Action
    {
        const XML_PATH_SIMPLE_EMAIL_TEMPLATE = 'simple/sendemail/template';

        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('velesfaq');
            $this->_addContent($this->getLayout()->createBlock('velesfaq/adminhtml_faq'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $id = (int) $this->getRequest()->getParam('id');

            $model = Mage::getModel('velesfaq/faq');

            if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
                $model->setData($data)->setId($id);
            } else {
                $model->load($id);
            }
            Mage::register('current_question', $model);

            $this->loadLayout()->_setActiveMenu('velesfaq');
            $this->_addLeft($this->getLayout()->createBlock('velesfaq/adminhtml_faq_edit_tabs'));
            $this->_addContent($this->getLayout()->createBlock('velesfaq/adminhtml_faq_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            $questionId = $this->getRequest()->getParam('id');

            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('velesfaq/faq');
                    $model->setData($data)->setId($questionId);

                    if(!empty($data['answer']) && $data['status'] > 1){
                        try {
                            $templateId = Mage::getStoreConfig(self::XML_PATH_SIMPLE_EMAIL_TEMPLATE);
                            $questionViewUrl = Mage::getUrl('faq/index/view');
                            $sender = array(
                                'name' => 'MageStore',
                                'email' => 'robot@mage.loc'
                            );

                            $email = $data['user_email'];
                            $emailName = 'Email Notification';
                            $vars = array(
                                "question_link"=>"".$questionViewUrl."?id=".$questionId."",
                                "question"=>"".$data['question']."",
                                "answer"=>"".$data['answer'].""
                            );
                            $storeId = Mage::app()->getStore()->getId();
                            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);

                            $this->_getSession()->addSuccess($this->__('Notification was successfully sent'));
                        } catch (Exception $e) {
                            $this->_getSession()->addError($e->getMessage("Error while sending mail"));
                        }

                        $model->setData('email_notification', '1');
                    }
                    $model->save();

                    $questionId = $model->getId();
                    $questionCategories = $model->getCategoriesCollection()->getAllIds();

                    if ($selectedCategories = $this->getRequest()->getParam('selected_categories', null)) {
                        $selectedCategoriesSerialized = Mage::helper('adminhtml/js')->decodeGridSerializedInput($selectedCategories);
                    } else {
                        $check_tab = $this->getRequest()->getParam('ajax_grid_in_category', null);
                        if(empty($check_tab)){
                            $selectedCategoriesSerialized = $questionCategories;
                        }else{
                            $selectedCategoriesSerialized = array();
                        }
                    }

                    $setCategories = array_diff($selectedCategoriesSerialized, $questionCategories);
                    $unsetCategories = array_diff($questionCategories, $selectedCategoriesSerialized);

                    foreach($unsetCategories as $categoryId){
                        $testColl = Mage::getModel('velesfaq/cq')
                            ->getCollection()
                            ->addFilter('category_id', $categoryId)
                            ->addFilter('question_id', $questionId);

                        try {
                            foreach($testColl as $item) {
                                Mage::getModel('velesfaq/cq')->setId($item->getId())->delete();
                            }
                        } catch (Exception $e) {
                            $this->_getSession()->addError($e->getMessage("Error while deleting"));
                        }
                    }
                    foreach($setCategories as $categoryId){
                        $newQuestionCategory = Mage::getModel('velesfaq/cq');
                        $newQuestionCategory->setData('category_id', $categoryId);
                        $newQuestionCategory->setData('question_id', $questionId);
                        $newQuestionCategory->save();
                    }

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Question was saved successfully'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array(
                        'id' => $this->getRequest()->getParam('id')
                    ));
                }
                return;
            }
            Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
            $this->_redirect('*/*/');
        }

        public function deleteAction()
        {
            if ($id = $this->getRequest()->getParam('id')) {
                try {
                    Mage::getModel('velesfaq/faq')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Question was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $questions = $this->getRequest()->getParam('faq', null);

            if (is_array($questions) && sizeof($questions) > 0) {
                try {
                    foreach ($questions as $id) {
                        Mage::getModel('velesfaq/faq')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d questions have been deleted', sizeof($questions)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select question'));
            }
            $this->_redirect('*/*');
        }

        public function categoryAction()
        {
            $id = (int) $this->getRequest()->getParam('id');

            $model = Mage::getModel('velesfaq/faq')->load($id);
            $request = Mage::app()->getRequest();

            Mage::register('current_question', $model);

            if ($request->isAjax()) {
                $this->loadLayout();
                $layout = $this->getLayout();

                $root = $layout->createBlock('core/text_list', 'root', array('output' => 'toHtml'));

                $grid = $layout->createBlock('velesfaq/adminhtml_faq_edit_tabs_category');
                $root->append($grid);

                if (!$request->getParam('grid_only')) {
                    $serializer = $layout->createBlock('adminhtml/widget_grid_serializer');
                    $serializer->initSerializerBlock($grid, 'getSelectedCategories', 'selected_categories', 'selected_categories');
                    $root->append($serializer);
                }

                $this->renderLayout();
            }
        }
    }
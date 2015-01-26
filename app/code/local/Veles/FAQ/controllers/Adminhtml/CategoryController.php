<?php
    class Veles_FAQ_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('velesfaq');
            $this->_addContent($this->getLayout()->createBlock('velesfaq/adminhtml_category'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $id = (int) $this->getRequest()->getParam('id');

            $model = Mage::getModel('velesfaq/category');

            if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
                $model->setData($data)->setId($id);
            } else {
                $model->load($id);
            }
            Mage::register('current_category', $model);

            $this->loadLayout()->_setActiveMenu('velesfaq');
            $this->_addLeft($this->getLayout()->createBlock('velesfaq/adminhtml_category_edit_tabs'));
            $this->_addContent($this->getLayout()->createBlock('velesfaq/adminhtml_category_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            $categoryId = $this->getRequest()->getParam('id');
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('velesfaq/category');

                    $model->setData($data)->setId($categoryId);
                    if(!$model->getCreated()){
                        $model->setCreated(now());
                    }
                    $model->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Category was saved successfully'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array(
                        'id' => $categoryId
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
                    Mage::getModel('velesfaq/category')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Category was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $questions = $this->getRequest()->getParam('category', null);

            if (is_array($questions) && sizeof($questions) > 0) {
                try {
                    foreach ($questions as $id) {
                        Mage::getModel('velesfaq/category')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d categories have been deleted', sizeof($questions)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select category'));
            }
            $this->_redirect('*/*');
        }

        public function faqAction()
        {
            $id = (int) $this->getRequest()->getParam('id');
            $model = Mage::getModel('velesfaq/category')->load($id);
            $request = Mage::app()->getRequest();
            Mage::register('current_category', $model);

            if ($request->isAjax()) {
                $this->loadLayout();

                $layout = $this->getLayout();

                $root = $layout->createBlock('core/text_list', 'root', array('output' => 'toHtml'));

                $grid = $layout->createBlock('velesfaq/adminhtml_category_edit_tabs_faq');
                $root->append($grid);

                $this->renderLayout();
            }
        }
    }
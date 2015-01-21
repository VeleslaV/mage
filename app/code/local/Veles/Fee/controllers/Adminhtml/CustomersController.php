<?php
    class Veles_Fee_Adminhtml_CustomersController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('fee');
            $this->_addContent($this->getLayout()->createBlock('fee/adminhtml_customers'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $id = (int) $this->getRequest()->getParam('id');
            Mage::register('current_customer', Mage::getModel('fee/fee')->load($id));

            $this->loadLayout()->_setActiveMenu('fee');
            $this->_addContent($this->getLayout()->createBlock('fee/adminhtml_customers_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('fee/fee');
                    $model->setData($data)->setId($this->getRequest()->getParam('id'));

                    $model->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Customer credit data was saved successfully'));
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
                    Mage::getModel('fee/fee')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Line was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $news = $this->getRequest()->getParam('customers', null);

            if (is_array($news) && sizeof($news) > 0) {
                try {
                    foreach ($news as $id) {
                        Mage::getModel('fee/fee')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d line have been deleted', sizeof($news)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select customers'));
            }
            $this->_redirect('*/*');
        }
    }
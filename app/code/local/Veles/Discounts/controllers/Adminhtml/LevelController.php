<?php
    class Veles_Discounts_Adminhtml_LevelController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('veles_discounts');
            $this->_addContent($this->getLayout()->createBlock('veles_discounts/adminhtml_level'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $id = (int) $this->getRequest()->getParam('id');
            Mage::register('current_level', Mage::getModel('veles_discounts/level')->load($id));

            $this->loadLayout()->_setActiveMenu('veles_discounts');
            $this->_addContent($this->getLayout()->createBlock('veles_discounts/adminhtml_level_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('veles_discounts/level');
                    $model->setData($data)->setId($this->getRequest()->getParam('id'));
                    $model->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Customer discount data was saved successfully'));
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
                    Mage::getModel('veles_discounts/level')->setId($id)->delete();
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
            $levels = $this->getRequest()->getParam('customers', null);

            if (is_array($levels) && sizeof($levels) > 0) {
                try {
                    foreach ($levels as $id) {
                        Mage::getModel('veles_discounts/discount')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d line have been deleted', sizeof($levels)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select customers'));
            }
            $this->_redirect('*/*');
        }
    }
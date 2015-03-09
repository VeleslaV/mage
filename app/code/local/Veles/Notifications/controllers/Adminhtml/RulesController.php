<?php
    class Veles_Notifications_Adminhtml_RulesController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('veles_notifications');
            $this->_addContent($this->getLayout()->createBlock('veles_notifications/adminhtml_rules'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $rule_id = (int) $this->getRequest()->getParam('id');
            $model = Mage::getModel('veles_notifications/rule');

            if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
                $model->setData($data)->setId($rule_id);
            } else {
                $model->load($rule_id);
            }
            Mage::register('current_rule', $model);

            $this->loadLayout()->_setActiveMenu('veles_notifications');
            $this->_addLeft($this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit_tabs'));
            $this->_addContent($this->getLayout()->createBlock('veles_notifications/adminhtml_rules_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('veles_notifications/rule');
                    $model->setData($data)->setId($this->getRequest()->getParam('rule_id')); // there
                    $model->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Notification rule data was saved successfully'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array(
                        'rule_id' => $this->getRequest()->getParam('rule_id')
                    ));
                }

                return;
            }
            Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
            $this->_redirect('*/*/');
        }

        public function deleteAction()
        {
            if ($id = $this->getRequeParam('rule_id')) {
                try {
                    Mage::getModel('veles_discounts/discount')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Line was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('rule_id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $customers = $this->getRequest()->getParam('rules', null);

            if (is_array($customers) && sizeof($customers) > 0) {
                try {
                    foreach ($customers as $id) {
                        Mage::getModel('veles_notifications/rule')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d line have been deleted', sizeof($customers)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select rules'));
            }
            $this->_redirect('*/*');
        }
    }
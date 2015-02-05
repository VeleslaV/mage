<?php
    class Veles_Discounts_Adminhtml_DiscountController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout()->_setActiveMenu('veles_discounts');
            $this->_addContent($this->getLayout()->createBlock('veles_discounts/adminhtml_discount'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $customer_id = (int) $this->getRequest()->getParam('id');
            Mage::register('current_customer', Mage::getModel('veles_discounts/discount')->load($customer_id));

            $this->loadLayout()->_setActiveMenu('veles_discounts');
            $this->_addContent($this->getLayout()->createBlock('veles_discounts/adminhtml_discount_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $model = Mage::getModel('veles_discounts/discount');
                    $model->setData($data)->setCustomerId($this->getRequest()->getParam('customer_id'));

                    $model->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Customer discount data was saved successfully'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array(
                        'customer_id' => $this->getRequest()->getParam('customer_id')
                    ));
                }

                return;
            }
            Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
            $this->_redirect('*/*/');
        }

        public function deleteAction()
        {
            if ($id = $this->getRequeParam('customer_id')) {
                try {
                    Mage::getModel('veles_discounts/discount')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Line was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('customer_id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $customers = $this->getRequest()->getParam('customers', null);

            if (is_array($customers) && sizeof($customers) > 0) {
                try {
                    foreach ($customers as $id) {
                        Mage::getModel('veles_discounts/discount')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('Total of %d line have been deleted', sizeof($customers)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select customers'));
            }
            $this->_redirect('*/*');
        }
    }
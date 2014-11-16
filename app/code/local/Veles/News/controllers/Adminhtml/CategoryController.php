<?php

class Veles_News_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('velesnews');
        $this->_addContent($this->getLayout()->createBlock('velesnews/adminhtml_category'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $model = Mage::getModel('velesnews/category');

        if ($data = Mage::getSingleton('adminhtml/session')->getFormData()) {
            $model->setData($data)->setId($id);
        } else {
            $model->load($id);
        }
        Mage::register('current_category', $model);

        $this->loadLayout()->_setActiveMenu('velesnews');

        //$this->_addLeft($this->getLayout()->createBlock('velesnews/adminhtml_category_edit_tabs'));
        $this->_addContent($this->getLayout()->createBlock('velesnews/adminhtml_category_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($data = $this->getRequest()->getPost()) {
            try {
                $helper = Mage::helper('velesnews');
                $model = Mage::getModel('velesnews/category');

                $model->setData($data)->setId($id);
                $model->save();

                $id = $model->getId();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Category was saved successfully'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array(
                    'id' => $id
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
                Mage::getModel('velesnews/category')->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Category was deleted successfully'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

}
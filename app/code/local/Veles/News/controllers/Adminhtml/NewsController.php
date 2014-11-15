<?php
    class Veles_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_Action
    {

        public function indexAction()
        {
            $this->loadLayout();
            $this->_setActiveMenu('velesnews');

            $contentBlock = $this->getLayout()->createBlock('velesnews/adminhtml_news');
            $this->_addContent($contentBlock);
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $id = (int)$this->getRequest()->getParam('id');
            $model = Mage::getModel('velesnews/news');

            if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
                $model->setData($data)->setId($id);
            } else {
                $model->load($id);
            }

            Mage::register('current_news', $model);

            $this->loadLayout()->_setActiveMenu('velesnews');
            $this->getLayout()->getBlock('head')->addItem('skin_js', 'veles_news/adminhtml/main.js');
            $this->getLayout()->getBlock('head')->addItem('skin_css', 'veles_news/adminhtml/styles.css');

            $this->_addLeft($this->getLayout()->createBlock('velesnews/adminhtml_news_edit_tabs'));
            $this->_addContent($this->getLayout()->createBlock('velesnews/adminhtml_news_edit'));
            $this->renderLayout();
        }

        public function saveAction()
        {
            $id = $this->getRequest()->getParam('id');
            if ($data = $this->getRequest()->getPost()) {
                try {
                    $helper = Mage::helper('velesnews');
                    $model = Mage::getModel('velesnews/news');

                    $model->setData($data)->setId($id);
                    if (!$model->getCreated()) {
                        $model->setCreated(now());
                    }
                    $model->save();
                    $id = $model->getId();

                    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg', 'jpeg'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $uploader->save($helper->getImagePath(), $id . '.jpg'); // Upload the image
                    } else {
                        if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                            @unlink($helper->getImagePath($id));
                        }
                    }

                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was saved successfully'));
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
                    Mage::getModel('velesnews/news')->setId($id)->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was deleted successfully'));
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $id));
                }
            }
            $this->_redirect('*/*/');
        }

        public function massDeleteAction()
        {
            $news = $this->getRequest()->getParam('news', null);

            if (is_array($news) && sizeof($news) > 0) {
                try {
                    foreach ($news as $id) {
                        Mage::getModel('velesnews/news')->setId($id)->delete();
                    }
                    $this->_getSession()->addSuccess($this->__('%d news have been deleted. For ever!', sizeof($news)));
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('Please select some news'));
            }
            $this->_redirect('*/*');
        }
    }
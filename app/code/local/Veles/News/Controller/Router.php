<?php
    class Veles_News_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
    {

        public function initControllerRouters($observer)
        {
            $front = $observer->getEvent()->getFront();
            $front->addRouter('velesnews', $this);
        }

        public function match(Zend_Controller_Request_Http $request)
        {
            $identifier = trim($request->getPathInfo(), '/');
            $cmd = explode('/', $identifier);

            if ($cmd[0] == 'news') {
                if (count($cmd) == 1) {
                    return $this->_fillRequest($request);
                } else {
                    $model = Mage::getModel('velesnews/news')->load($cmd[1], 'link');
                    if ($model->getId()) {
                        $params = array(
                            'id' => $model->getId()
                        );
                        return $this->_fillRequest($request, $params, 'index', 'show');
                    }
                }
            }
            return false;
        }

        protected function _fillRequest($request, $cmd = array(), $controller = 'index', $action = 'index')
        {
            $request->setModuleName('news')
                ->setControllerName($controller)
                ->setActionName($action)
                ->setParam('is_routed', 1);
            if (is_array($cmd) && count($cmd)) {
                foreach ($cmd as $key => $value) {
                    $request->setParam($key, $value);
                }
            }

            $request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $request->getPathInfo());
            return true;
        }
    }
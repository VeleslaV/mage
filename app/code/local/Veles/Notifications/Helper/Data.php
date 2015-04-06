<?php

/**
 * Class Veles_Notifications_Helper_Data
 */
class Veles_Notifications_Helper_Data extends Mage_Core_Helper_Abstract
    {
        const XML_PATH_DEFAULT_EMAIL_TEMPLATE = 'veles_notifications/email/email_template';


        /**
         * @param string $logString
         */
        public function createLog($logString)
        {
            Mage::log($logString, null, 'cronJob.log');
        }



        /**
         * @return array
         */
        public function getBaseStatuses()
        {
            return array(
                array('value'=>'0','label'=>'Disable'),
                array('value'=>'1','label'=>'Enable')
            );
        }



        /**
         * @return array
         */
        public function getBaseStatusesOptions()
        {
            return array(
                '0'=>'Disable',
                '1'=>'Enable'
            );
        }



        /**
         * @return array
         */
        public function getQueueStatuses()
        {
            return array(
                array('value'=>'0','label'=>'Pending'),
                array('value'=>'1','label'=>'Success'),
                array('value'=>'2','label'=>'Fail')
            );
        }



        /**
         * @return array
         */
        public function getQueueStatusesOptions()
        {
            return array(
                '0'=>'Pending',
                '1'=>'Success',
                '2'=>'Fail',
                '3'=>'Canceled'
            );
        }



        /**
         * @return array
         */
        public function getScheduleOptions()
        {
            return array(
                array('value'=>'1 day','label'=>'1 day'),
                array('value'=>'2 days','label'=>'2 days'),
                array('value'=>'5 days','label'=>'5 days'),
                array('value'=>'10 days','label'=>'10 days'),
                array('value'=>'20 days','label'=>'20 days'),
                array('value'=>'30 days','label'=>'30 days')
            );
        }



        /**
         * @return array
         */
        public function getEmailTemplatesOptions()
        {
            $templateCollection =  Mage::getResourceSingleton('core/email_template_collection');
            $emailTemplateOptions = $this->createDropDownOptions(
                $templateCollection, array('valueParam' => 'template_id', 'labelParam' => 'template_code')
            );

            return $emailTemplateOptions;
        }



        /**
         * @return string
         */
        public function getShoppingCartPriceRuleIdFromModuleOptions()
        {
            $shoppingCartPriceRuleId = Mage::getStoreConfig('veles_notifications_options/rules/shopping_cart_rule_id');

            return $shoppingCartPriceRuleId;
        }



        /**
         * @param string $type
         * @return array
         */
        public function getRuleEvents($type)
        {
            $eventsCollection = Mage::getModel('veles_notifications/event')->getCollection();
            $eventsCollection->addFieldToFilter('main_table.event_status', array('eq'=>'1'));
            $eventsCollection->addFieldToFilter('main_table.event_type', array('eq'=>$type));
            $eventsCollection->getSelect();

            $ruleEventsOptions = $this->createDropDownOptions(
                $eventsCollection, array('valueParam' => 'event_id', 'labelParam' => 'event_title')
            );

            return $ruleEventsOptions;
        }



        /**
         * @return array
         */
        public function getCustomersGroups()
        {
            $groupsCollection = Mage::getModel('customer/group')->getCollection();

            $customersGroupsOptions = $this->createDropDownOptions(
                $groupsCollection, array('valueParam' => 'customer_group_id', 'labelParam' => 'customer_group_code')
            );

            return $customersGroupsOptions;
        }



        /**
         * @param object
         * @param array
         * @return array
         */
        public function createDropDownOptions($itemsCollection, $itemsParams)
        {
            $options[0] = array('value' => '', 'label' => '');
            $itemsCount = 1;

            foreach ($itemsCollection as $item) {
                $options[$itemsCount]['value'] = $item->getData($itemsParams['valueParam']);
                $options[$itemsCount]['label'] = $item->getData($itemsParams['labelParam']);

                $itemsCount++;
            }

            return $options;
        }



        /**
         * @param object
         * @return bool
         */
        public function sendNotificationEmail($queueItem)
        {
            //get rule for current queue item
            $ruleModel = Mage::getModel('veles_notifications/rule');
            $ruleCollection = $ruleModel->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.rule_id', array('eq'=>$queueItem->getData('rule_id')));
            $ruleCollection->getSelect()->limit(1);
            $rule = $ruleCollection->getFirstItem();

            $templateId = $rule->getData('email_template_id');
            $productModel = Mage::getModel('catalog/product');
            $_product = $productModel->load($queueItem->getData('product'));

            $sender = array(
                'name' => $rule->getData('sender_name'),
                'email' => $rule->getData('sender_email')
            );
            $email = $queueItem->getData('customer_email');
            $emailSubject = 'Default Notification Email';
            $vars = array(
                'customer_name' => $queueItem->getData('customer_name'),
                'coupon' => $queueItem->getData('coupon'),
                'products_count' => $rule->getData('products_count'),
                'bought_product' => $_product->getName()
            );

            $storeId = Mage::app()->getStore()->getId();
            Mage::getModel('core/email_template')->sendTransactional(
                $templateId,
                $sender,
                $email,
                $emailSubject,
                $vars,
                $storeId
            );

            return false;
        }


    }
<?php
    class Veles_Notifications_Helper_Data extends Mage_Core_Helper_Abstract
    {
        const XML_PATH_DEFAULT_EMAIL_TEMPLATE = 'veles_notifications/email/template';

        public function createLog($logString)
        {
            Mage::log($logString, null, 'cronJob.log');
        }

        public function getBaseStatuses()
        {
            return array(
                array('value'=>'1','label'=>'Enable'),
                array('value'=>'0','label'=>'Disable')
            );
        }

        public function getBaseStatusesOptions()
        {
            return array(
                '0'=>'Disable',
                '1'=>'Enable'
            );
        }

        public function getQueueStatuses()
        {
            return array(
                array('value'=>'0','label'=>'Pending'),
                array('value'=>'1','label'=>'Success'),
                array('value'=>'2','label'=>'Fail')
            );
        }

        public function getQueueStatusesOptions()
        {
            return array(
                '0'=>'Pending',
                '1'=>'Success',
                '2'=>'Fail'
            );
        }

        public function getRuleEvents()
        {
            $eventsCollection = Mage::getModel('veles_notifications/event')->getCollection();
            $eventsCollection->addFieldToFilter('main_table.event_status', array('eq'=>'1'));
            $eventsCollection->getSelect();

            $ruleEventsOptions = $this->createDropDownOptions(
                $eventsCollection, array('valueParam' => 'event_id', 'labelParam' => 'event_title')
            );

            return $ruleEventsOptions;
        }

        public function getCustomersGroups()
        {
            $groupsCollection = Mage::getModel('customer/group')->getCollection();

            $customersGroupsOptions = $this->createDropDownOptions(
                $groupsCollection, array('valueParam' => 'customer_group_id', 'labelParam' => 'customer_group_code')
            );

            return $customersGroupsOptions;
        }

        private function createDropDownOptions($itemsCollection, $itemsParams)
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

        public function sendNotificationEmail($queueItem)
        {
            $templateId = Mage::getStoreConfig(self::XML_PATH_DEFAULT_EMAIL_TEMPLATE);

            //get rule for current queue item
            $ruleModel = Mage::getModel('veles_notifications/rule');
            $ruleCollection = $ruleModel->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('main_table.rule_id', array('eq'=>$queueItem->getData('rule_id')));
            $ruleCollection->getSelect()->limit(1);
            $rule = $ruleCollection->getFirstItem();

            $sender = array(
                'name' => $rule->getData('sender_name'),
                'email' => $rule->getData('sender_email')
            );
            $email = $queueItem->getData('customer_email');
            $emailSubject = 'Default Notification Email';
            $vars = array(
                'customer_name' => $queueItem->getData('customer_name')
            );

            $storeId = Mage::app()->getStore()->getId();
            Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $email, $emailSubject, $vars, $storeId);

            return false;
        }


    }
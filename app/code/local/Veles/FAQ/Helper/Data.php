<?php
    class Veles_FAQ_Helper_Data extends Mage_Core_Helper_Abstract
    {
        public function getCategoriesList()
        {
            $categories = Mage::getModel('velesfaq/category')->getCollection()->load();
            $output = array();
            foreach($categories as $category){
                $output[$category->getId()] = $category->getTitle();
            }
            return $output;
        }

        public function getCategoriesOptions()
        {
            $categories = Mage::getModel('velesfaq/category')->getCollection()->load();
            $options = array();
            $options[] = array(
                'label' => '',
                'value' => ''
            );
            foreach ($categories as $category) {
                $options[] = array(
                    'label' => $category->getTitle(),
                    'value' => $category->getId(),
                );
            }
            return $options;
        }

        public function getStatusesList()
        {
            $statuses = Mage::getModel('velesfaq/status')->getCollection()->load();
            $output = array();
            foreach($statuses as $status){
                $output[$status->getId()] = $status->getTitle();
            }
            return $output;
        }

        public function getStatusesOptions()
        {
            $statuses = Mage::getModel('velesfaq/status')->getCollection()->load();
            $options = array();
            $options[] = array(
                'label' => '',
                'value' => ''
            );
            foreach ($statuses as $status) {
                $options[] = array(
                    'label' => $status->getTitle(),
                    'value' => $status->getId(),
                );
            }
            return $options;
        }

        public function joinMyCategoriesCollection($id)
        {
            $cq_model = Mage::getModel('velesfaq/category');
            $collection = $cq_model->getCollection();
            $collection->getSelect()->join(array('cq'=>'veles_faq_category_question'), 'main_table.category_id = cq.category_id AND cq.question_id = '.$id.'', 'cq.question_id');

            return $collection;
        }

        public function joinMyQuestionsCollection($id)
        {
            $cq_model = Mage::getModel('velesfaq/faq');
            $collection = $cq_model->getCollection();
            $collection->getSelect()->join(array('cq'=>'veles_faq_category_question'), 'main_table.question_id = cq.question_id AND cq.category_id = '.$id.'', 'cq.category_id');

            return $collection;
        }
    }
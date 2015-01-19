<?php
class Veles_FAQ_Block_Category extends Mage_Core_Block_Template
{
    public function getFaqByCategoryCollection($category_id, $status_id)
    {
        $model = Mage::getModel('velesfaq/faq');
        $collection = $model->getCollection();
        $collection->getSelect()->join(array('cq'=>'veles_faq_category_question'), 'main_table.question_id = cq.question_id AND cq.category_id = '.$category_id.' AND main_table.status = '.$status_id.'', 'cq.category_id');

        return $collection;
    }
}
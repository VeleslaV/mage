<?php
    class Veles_RecentlySold_Model_WidgetOptions extends Mage_Core_Model_Abstract
    {
        public function toOptionArray()
        {
            return array(
                array('value' => '2', 'label' => '2'),
                array('value' => '4', 'label' => '4'),
                array('value' => '6', 'label' => '6'),
                array('value' => '8', 'label' => '8'),
                array('value' => '10', 'label' => '10')
            );
        }
    }
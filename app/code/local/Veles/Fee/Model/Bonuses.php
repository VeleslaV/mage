<?php
    class Veles_Fee_Model_Bonuses
    {
        public function toOptionArray()
        {
            return array(
                array('value'=>"", 'label'=>""),
                array('value'=>"percentage", 'label'=>Mage::helper('fee')->__('Percentage')),
                array('value'=>"static", 'label'=>Mage::helper('fee')->__('Static')),
            );
        }
    }
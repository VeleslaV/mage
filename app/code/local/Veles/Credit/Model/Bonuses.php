<?php
    class Veles_Credit_Model_Bonuses
    {
        public function toOptionArray()
        {
            return array(
                array('value'=>"", 'label'=>""),
                array('value'=>"percentage", 'label'=>Mage::helper('credit')->__('Percentage')),
                array('value'=>"static", 'label'=>Mage::helper('credit')->__('Static')),
            );
        }
    }
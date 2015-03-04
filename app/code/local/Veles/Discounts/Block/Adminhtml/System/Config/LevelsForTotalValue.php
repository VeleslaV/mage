<?php
    class Veles_Discounts_Block_Adminhtml_System_Config_LevelsForTotalValue extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
    {
        private $_arrayRowsCache;

        public function __construct()
        {
            $this->addColumn('level_id', array(
                'label' => Mage::helper('adminhtml')->__('Level #'),
                'size'  => 28,
                'class'  => 'vd_level_id'
            ));
            $this->addColumn('level_title', array(
                'label' => Mage::helper('adminhtml')->__('Level Title'),
                'size'  => 28,
                'class'  => 'vd_level_title'
            ));
            $this->addColumn('level_activate_on', array(
                'label' => Mage::helper('adminhtml')->__('Level Condition'),
                'size'  => 28,
                'class'  => 'vd_level_activate_on'
            ));
            $this->addColumn('level_discount', array(
                'label' => Mage::helper('adminhtml')->__('Level Discount'),
                'size'  => 28,
                'class'  => 'vd_level_discount'
            ));
            $this->_addAfter = false;
            $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add New Discount Level');

            parent::__construct();
        }

        protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
        {
            $this->setElement($element);
            $fieldId = $this->getElement()->getId();

            $html = '
                <div id="'.$fieldId.'">
                    '.$this->_toHtml().'
                </div>
            ';

            $this->_arrayRowsCache = null;

            return $html;
        }
    }
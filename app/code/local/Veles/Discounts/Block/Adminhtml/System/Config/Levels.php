<?php
    class Veles_Discounts_Block_Adminhtml_System_Config_Levels extends Mage_Adminhtml_Block_System_Config_Form_Field
    {
        protected $_addRowButtonHtml = array();
        protected $_removeRowButtonHtml = array();

        protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
        {
            $this->setElement($element);
            $fieldId = $this->getElement()->getId();
            $headerSelector = "vdl_header_".$fieldId;
            $containerSelector = "vdl_container_".$fieldId;
            $templateSelector = "vdl_template_".$fieldId;

            $html = '
                <div id="'.$fieldId.'">
                    <div id="'.$templateSelector.'" style="display:none">'.$this->_getRowTemplateHtml().'</div>
                    <div id="'.$headerSelector.'">
                        <span style="display: inline-block; width: 65px; margin: 0px 10px 0px 0px; font-weight: 700;">Level #</span>
                        <span style="display: inline-block; width: 210px; margin: 0px 10px 0px 0px; font-weight: 700;">Level Title</span>
                        <span style="display: inline-block; width: 210px; margin: 0px 10px 0px 0px; font-weight: 700;">Activate on</span>
                        <span style="display: inline-block; width: 210px; margin: 0px 10px 0px 0px; font-weight: 700;">Percentage discounts</span>
                    </div>
                    <ul id="'.$containerSelector.'" style="width: 860px;">';
                        if($this->_getValue('level_id')) {
                            foreach ($this->_getValue('level_id') as $i => $f) {
                                if ($i) {
                                    $html .= $this->_getRowTemplateHtml($i);
                                }
                            }
                        }
            $html .= '
                    </ul>
                    '.$this->_getAddRowButtonHtml($containerSelector, $templateSelector, $this->__('Add New Discount Level')).'
                </div>
            ';

            return $html;
        }

        protected function _getRowTemplateHtml($rowIndex = 0)
        {
            $html = '
                <li>
                    <div style="margin: 5px 0 10px;">
                        <input style="width: 50px; margin: 0px 10px 0px 0px; padding: 0px 3px;" name="'. $this->getElement()->getName().'[level_id][]" value="'.$this->_getValue('level_id/'.$rowIndex).'" '.$this->_getDisabled().'/>
                        <input style="width: 200px; margin: 0px 10px 0px 0px; padding: 0px 3px;" name="'. $this->getElement()->getName().'[level_title][]" value="'.$this->_getValue('level_title/'.$rowIndex).'" '.$this->_getDisabled().'/>
                        <input style="width: 200px; margin: 0px 10px 0px 0px; padding: 0px 3px;" name="'. $this->getElement()->getName().'[level_activate_on][]" value="'.$this->_getValue('level_activate_on/'.$rowIndex).'" '.$this->_getDisabled().'/>
                        <input style="width: 200px; margin: 0px 10px 0px 0px; padding: 0px 3px;" name="'. $this->getElement()->getName().'[level_discount][]" value="'.$this->_getValue('level_discount/'.$rowIndex).'" '.$this->_getDisabled().'/>
                        '.$this->_getRemoveRowButtonHtml().'
                    </div>
                </li>
            ';

            return $html;
        }

        protected function _getDisabled(){ return $this->getElement()->getDisabled()?' disabled':''; }

        protected function _getValue($key){ return $this->getElement()->getData('value/'.$key); }

        protected function _getAddRowButtonHtml($container, $template, $title='Add')
        {
            if (!isset($this->_addRowButtonHtml[$container])) {
                $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('add '.$this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.insert($('".$container."'), {bottom: $('".$template."').innerHTML})")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
            }
            return $this->_addRowButtonHtml[$container];
        }

        protected function _getRemoveRowButtonHtml($selector = 'li', $title = 'Delete')
        {
            if (!$this->_removeRowButtonHtml) {
                $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('delete v-middle '.$this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.remove($(this).up('".$selector."'))")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
            }
            return $this->_removeRowButtonHtml;
        }
    }
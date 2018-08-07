<?php

class Wyomind_Massstockupdate_Model_Mysql4_System extends Wyomind_Massstockupdate_Model_Mysql4_Abstract
{

    public function getFields($fieldset = null, $model = false, $form = false)
    {
        if ($fieldset == null) {
            return true;
        }


        $fieldset->addField('has_header', 'select', array(
            'name' => 'has_header',
            'value' => ('0' == $model->getHasHeader()) ? $model->getHasHeader() : 1,
            'label' => Mage::helper($this->module)->__('The first line is a header'),
            'options' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'class' => 'updateOnChange'
        ));

        $fieldset->addField('line_filter', 'text', array(
            'name' => 'line_filter',
            'value' => $model->getLineFilter(),
            'label' => Mage::helper($this->module)->__('Filter lines'),
            'note' => Mage::helper($this->module)->__('<span class="notice">Leave empty to export all lines</span><br/>'
                    . '- Type the numbers of the lines you want to import.<br/>'
                    . '- Use a dash (-) to denote a range of lines.<br/>'
                    . 'e.g: 8-10 means lines 8,9,10 will be imported<br/>'
                    . '- Use a plus (+) to import all lines from a line number.<br/>'
                    . 'e.g: 4+ means all lines from line 4 will be imported<br/>'
                    . '- Separate each line or range with a comma (,)<br/>'
                    . 'e.g: 2,6-10,15+ means lines 2,6,7,8,9,10,15,16,17,... will be exported'),
            'class' => 'updateOnChange'
        ));
        
        return $fieldset;
    }

}

<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tab_Advanced extends Mage_Adminhtml_Block_Widget_Form
{
    public $module = "massstockupdate";

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $model = Mage::getModel($this->module .'/import');
        $model->load($this->getRequest()->getParam('profile_id'));
        $this->setForm($form);

        foreach (Mage::helper($this->module . "/data")->modules as $module) {
            $resource = Mage::getResourceSingleton($this->module . "/" . $module);

            if ($resource->hasFields()) {
                $fieldset = $form->addFieldset($this->module . '_' . $module . '_option', ['legend' => __(ucfirst($module) . ' Settings')]);
                $fieldset = $resource->getFields($fieldset, $model, $this);
            }
        }

        return parent::_prepareForm();
    }
}
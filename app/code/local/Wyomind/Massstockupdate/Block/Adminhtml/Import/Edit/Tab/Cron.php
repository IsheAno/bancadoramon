<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tab_Cron extends Mage_Adminhtml_Block_Widget_Form
{
    public $module = "massstockupdate";

    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $model = Mage::getModel($this->module .'/import');

        $model->load($this->getRequest()->getParam('profile_id'));

        $this->setForm($form);
        $fieldset = $form->addFieldset($this->module.'_form', array('legend' => $this->__('Scheduled tasks')));


        $this->setTemplate('massstockupdate/cron.phtml');




        return parent::_prepareForm();
    }

}

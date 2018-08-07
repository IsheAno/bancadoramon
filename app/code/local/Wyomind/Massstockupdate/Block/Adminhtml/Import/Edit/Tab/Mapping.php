<?php

class Wyomind_Massstockupdate_Block_Adminhtml_Import_Edit_Tab_Mapping extends Mage_Adminhtml_Block_Widget_Form
{

    public $module = "massstockupdate";
	private $_mapping=false;

	
	
    protected function _prepareForm()
    {
        $this->setTemplate('massstockupdate/mapping.phtml');

        $model = Mage::getModel($this->module . '/import');
        $model->load($this->getRequest()->getParam('profile_id'));

        $form = new Varien_Data_Form();
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getModel()
    {
        $model = Mage::getModel($this->module . '/import');
        $model->load($this->getRequest()->getParam('profile_id'));
        return $model;
    }

    public function getRow($helper, $storeviewArray = array(0), $id = false, $source = false,
            $default = "", $script = "", $enabled = true)
    {
        if(!$this->_mapping){
			$this->_mapping=Mage::helper($helper)->getMappingDropdown(false);
		}
		$mapping=$this->_mapping;


        $storeviews = "<ul>";

        foreach ($mapping["storeviews"]["children"] as $website) {
            $storeviews .= '<li class = "website"><span class = "label">' . $website["label"] . '</span>';
            $storeviews .= "<ul>";
            foreach ($website["children"] as $store) {

                $storeviews .= '<li class = "store"><span class = "label">' . $store["label"] . '</span>';
                $storeviews .= "<ul>";
                foreach ($store["children"] as $view) {
                    $checked = "";
                    if (in_array($view['value'], $storeviewArray)) {
                        $checked = "checked";
                    }
                    $storeviews .= '<li class = "store-view"><input ' . $checked . ' type = "checkbox" value = "' . $view["value"] . '"><span class = "label">' . $view["label"] . '</span>';
                    $storeviews .='</li>';
                }
                $storeviews.="</ul>";
                $storeviews .='</li>';
            }
            $storeviews.="</ul>";
            $storeviews .='</li>';
        }
        $disabled = ($enabled) ? "" : "disabled";
        $select = ' <select class="attribute '.$disabled.'">';
        $select.='<option value="Ignored/ignored" >-- ' . __("Select an attribute") . ' --</option>';

        foreach ($mapping as $label => $attributes) {
            if (!in_array($label, array("storeviews", "Other"))) {
                $select.='<optgroup  label="' . $label . '" >';
                foreach ($attributes as $attribute) {
                    $selected = "";
                    if ($id == $attribute['id']) {

                        $selected = "selected";
                    }
                    $select.='<option class="' . $attribute['style'] . '" ' . $selected . ' value="' . $attribute['id'] . '" >' . $label . ' | ' . addslashes($attribute['label']) . '</option>';
                }
                $select.='</optgroup >';
            }
        }
        $select.= '</select>';
        $defaultScopeChecked = "";
        if (in_array(0, $storeviewArray)) {
            $defaultScopeChecked = "checked";
        }
        $storeviews.="</ul>";


        $active = ($script == "") ? "" : "active";
        $invisible = ($script == "") ? ($source == "") ? "" : "invisible" : "invisible";

        $template = ' <li class = "sortable">
        <input type = "hidden" class = "agregate" value="{}"/>
        <div class = "mapping-row">
        <span class = "cell body"><div class = "icon grip"></div></span>
        <span class = "cell body">' . $select . ' 

        </span>
        <span class = "cell body"><div class = "icon link ' . $disabled . '"></div></span>
        <span class = "cell body"><select class = "source ' . $disabled . '"  ><option value = "" >' . $source . '</option></select></span>
        <span class = "cell body"><input type = "text" class = "default ' . $disabled . ' ' . $invisible . '" value="' . $default . '" /></span>
        <span class = "cell body"><div class = "icon code ' . $active . ' ' . $disabled . '"></div><textarea class = "scripting hidden">' . ($script) . '</textarea></span>
        <span class = "cell body"><div class = "icon trash"></div></span>
        </div>
        <div class = "scope-row">
        <span class = "cell body" colspan = "4">
        <a class = "scope-link">
        <div class = "icon chevron-right"></div>
        <span class = "scope-apply-to">Apply to</span>
        <span class = "scope-summary"></span>
        </a>
        <div class = "scope-details hidden">
        <div class = "icon chevron-down"></div>
        <div class = "all-store-views"><input type = "checkbox" class = "default-scope" value = "0" ' . $defaultScopeChecked . '>' . $mapping["storeviews"]["label"] . ' (' . __("Apply to all store views") . ' )  </div>
        ' . $storeviews . '
        <button type = "button" class = "apply">Apply</button> <button type = "button" class = "reset">Reset</button>
        </div>
        </span>
        </div>
        </li>';

        return str_replace(array("\n", "\r"), "", $template);
    }

}

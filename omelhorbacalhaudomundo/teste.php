<?php  
define('BX_DOL_LOCALE_DATE_SHORT', 4);
$iCode = BX_DOL_LOCALE_DATE_SHORT;
   $teste =  (int)$iCode == 6 ? 'r' : getLocaleFormat($iCode);
echo $teste;

    
?>

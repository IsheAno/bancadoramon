<?php

    class INT_Lowstock_Model_Observer
    {
        public function getProStock(Varien_Event_Observer $observer)
        {
            $is_enable = Mage::getStoreConfig('lowstock/group_lowstock/status_select');
            if($is_enable == 1)
            {
                $alt_qty = Mage::getStoreConfig('lowstock/group_notificationsetting/minstock_text_field');
                $product      = $observer->getEvent()->getProduct();
                $product_name = $product->getName();
                $sku = $product->getSku();
                $remaning_qty = $product->getStockItem()->getQty();
            
                if($remaning_qty == $alt_qty || $alt_qty > $remaning_qty)
                {
                    $fromEmail            = Mage::getStoreConfig('lowstock/group_notificationsetting/senderemail_text_field');
                    $fromName             = "Support";
                    $toEmail              = Mage::getStoreConfig('lowstock/group_notificationsetting/receiveremail_text_field');
                    $toName               = "Admin";
                    $email_template       = Mage::getStoreConfig('lowstock/group_notificationsetting/notification_email_template');
                    $email_template_value = array('{product_name}','{sku}','{qty}');
                    $replace_value        = array($product_name,$sku,$remaning_qty);
                    $message              = str_replace($email_template_value,$replace_value,$email_template);
                    $subject              = 'Low Stock Notification for '.$product_name;
        
                    try{
                        $mail = new Zend_Mail();
                        $mail->setFrom($fromEmail, $fromName);
                        $mail->addTo($toEmail, $toName);
                        $mail->setSubject($subject);
                        $mail->setBodyHtml($message); 
                        $mail->send();
        
                    }catch(Exception $e){
                        echo $e->getMassage();
                    }
                }
            }
        }
        
        public function checkStock(Varien_Event_Observer $observer)
        {
            $is_enable = Mage::getStoreConfig('lowstock/group_lowstock/status_select');
            if($is_enable == 1)
            {
                $order = $observer->getEvent()->getOrder();
                $all_items = $order->getAllItems();
                
                if(count($all_items) > 0)
                {
                    foreach($all_items as $item)
                    {     
                        $product = Mage::getModel('catalog/product')->load($item->getProductId());
                        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
                        $remaning_qty = round($stockItem->getQty());
                        $sku = $item->getSku();
                        $product_name = $product->getName();
                        $alt_qty = Mage::getStoreConfig('lowstock/group_notificationsetting/minstock_text_field');
                        if($remaning_qty == $alt_qty || $alt_qty > $remaning_qty)
                        {
                            $fromEmail            = Mage::getStoreConfig('lowstock/group_notificationsetting/senderemail_text_field');
                            $fromName             = "Support";
                            $toEmail              = Mage::getStoreConfig('lowstock/group_notificationsetting/receiveremail_text_field');
                            $toName               = "Admin";
                            $email_template       = Mage::getStoreConfig('lowstock/group_notificationsetting/notification_email_template');
                            $email_template_value = array('{product_name}','{sku}','{qty}');
                            $replace_value        = array($product_name,$sku,$remaning_qty);
                            $message              = str_replace($email_template_value,$replace_value,$email_template);
                            $subject              = 'Low Stock Notification for '.$product_name;
                
                            try{
                                $mail = new Zend_Mail();
                                $mail->setFrom($fromEmail, $fromName);
                                $mail->addTo($toEmail, $toName);
                                $mail->setSubject($subject);
                                $mail->setBodyHtml($message); 
                                $mail->send();
                            }catch(Exception $e){}                            
                        }
                    }
                }               
            }
        }
    }

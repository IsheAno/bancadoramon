<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


class Amasty_InvisibleCaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
    const PARAM_NAME_REFERER_URL        = 'referer_url';
    const PARAM_NAME_BASE64_URL         = 'r64';
    const PARAM_NAME_URL_ENCODED        = 'uenc';

    public function verifyCaptcha($token)
    {
        $googleVerify = new Varien_Http_Client('https://www.google.com/recaptcha/api/siteverify');
        $googleVerify->setMethod(Varien_Http_Client::POST);
        $googleVerify->setParameterPost('secret', Mage::getStoreConfig('aminvisiblecaptcha/general/captchaSecret'));
        $googleVerify->setParameterPost('response', $token);
        try {
            $googleResponse = $googleVerify->request();
            if ($googleResponse->isSuccessful()) {
                if (!empty($googleResponse) && array_key_exists('success', $answer = json_decode($googleResponse->getBody()))) {
                    $success = $answer->success;
                    if ($success) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    public function getCaptchaUrls()
    {
        $urls = trim(Mage::getStoreConfig('aminvisiblecaptcha/general/captchaUrls'));
        $urlsList = preg_split('|\s*[\r\n]+\s*|', $urls, -1, PREG_SPLIT_NO_EMPTY);
        return $urlsList;
    }

    public function getCaptchaSelectors()
    {
        $selectors = trim(Mage::getStoreConfig('aminvisiblecaptcha/general/captchaSelectors'));
        $selectorsList = preg_split('|\s*[\r\n]+\s*|', $selectors, -1, PREG_SPLIT_NO_EMPTY);
        return $selectorsList;
    }
}

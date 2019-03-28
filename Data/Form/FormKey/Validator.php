<?php

namespace Khoazero123\DevFix\Data\Form\FormKey;

use Magento\Framework\Encryption\Helper\Security;

class Validator extends \Magento\Framework\Data\Form\FormKey\Validator {

    /**
     * Validate form key
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function validate(\Magento\Framework\App\RequestInterface $request) {
        $result = parent::validate($request);
        if($result === false) {
            $formKey = $request->getParam('form_key', null); 
            $_formKey = $this->_formKey->getFormKey();
            $result = $formKey && Security::compareStrings($formKey, $_formKey);
            return !$result;
        }
        return $result;
    }
}

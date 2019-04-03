<?php

namespace Khoazero123\DevFix\Model;

use Khoazero123\DevFix\Helper\Data as Helper;

class Store extends \Magento\Store\Model\Store {

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl($type = 'link', $secure = null)
    {
        $originBaseUrl = parent::getBaseUrl($type, $secure);

        $parseOriginUrl = parse_url($originBaseUrl);
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $currentUrl = $urlInterface->getCurrentUrl();

        $parseCurrentUrl = parse_url($currentUrl);

        $parseOriginUrl['host'] = $parseCurrentUrl['host'];
        $parseOriginUrl['port'] = $parseCurrentUrl['port'];

        $baseUrl = Helper::build_url($parseOriginUrl);
        return $baseUrl;
    }
}

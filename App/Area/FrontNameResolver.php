<?php

namespace Khoazero123\DevFix\App\Area;

use Magento\Backend\Setup\ConfigOptionsList;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

use Khoazero123\DevFix\Helper\Data as Helper;

class FrontNameResolver extends \Magento\Backend\App\Area\FrontNameResolver {

    /**
     * @param \Magento\Backend\App\Config $config
     * @param DeploymentConfig $deploymentConfig
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Backend\App\Config $config,
        DeploymentConfig $deploymentConfig,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($config, $deploymentConfig, $scopeConfig);
        $this->config = $config;
        $this->defaultFrontName = $deploymentConfig->get(ConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Return whether the host from request is the backend host
     *
     * @return bool
     */
    public function isHostBackend()
    {
        $isHostBackend = parent::isHostBackend();
        if ($this->scopeConfig->getValue(self::XML_PATH_USE_CUSTOM_ADMIN_URL, ScopeInterface::SCOPE_STORE)) {
            $backendUrl = $this->scopeConfig->getValue(self::XML_PATH_CUSTOM_ADMIN_URL, ScopeInterface::SCOPE_STORE);
        } else {
            $backendUrl = $this->scopeConfig->getValue(Store::XML_PATH_UNSECURE_BASE_URL, ScopeInterface::SCOPE_STORE);
        }

        $parseOriginUrl = parse_url($backendUrl);
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $currentUrl = $urlInterface->getCurrentUrl();

        $parseCurrentUrl = parse_url($currentUrl);

        $parseOriginUrl['scheme'] = $parseCurrentUrl['scheme'];
        $parseOriginUrl['host'] = $parseCurrentUrl['host'];
        $parseOriginUrl['port'] = $parseCurrentUrl['port'];

        $backendUrl = Helper::build_url($parseOriginUrl);

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $result = stripos($this->getHostWithPort($backendUrl), $host) !== false;
        return $result;
    }

    /**
     * Get host with port
     *
     * @param string $url
     * @return mixed|string
     */
    private function getHostWithPort($url)
    {
        // return parent::getHostWithPort($url);
        $scheme = parse_url(trim($url), PHP_URL_SCHEME);
        $host = parse_url(trim($url), PHP_URL_HOST);
        $port = parse_url(trim($url), PHP_URL_PORT);
        if (!$port) {
            $port = isset($this->standardPorts[$scheme]) ? $this->standardPorts[$scheme] : null;
        }
        $result = isset($port) ? $host . ':' . $port : $host;
        return $result; 
    }

}

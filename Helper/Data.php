<?php

namespace Khoazero123\DevFix\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {
    public static function http_parse_query($query) {
        $parameters = array();
        $queryParts = explode('&', $query);
        foreach ($queryParts as $queryPart) {
            $keyValue = explode('=', $queryPart, 2);
            $parameters[$keyValue[0]] = $keyValue[1];
        }
        return $parameters;
    }
    
    public static function build_url(array $parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . 
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . 
            (isset($parts['user']) ? "{$parts['user']}" : '') . 
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') . 
            (isset($parts['user']) ? '@' : '') . 
            (isset($parts['host']) ? "{$parts['host']}" : '') . 
            (isset($parts['port']) ? ":{$parts['port']}" : '') . 
            (isset($parts['path']) ? "{$parts['path']}" : '') . 
            (isset($parts['query']) ? "?{$parts['query']}" : '') . 
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}

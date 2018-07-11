<?php
namespace App\Test\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function variableDump($variable)
    {
        \Codeception\Util\Debug::debug($variable);
    }
    
    public function getConfigParameter($parameter, $module = 'WebDriver')
    {
        return rtrim($this->getModule($module)->_getConfig($parameter), '/');
    }

    public function getConfigUrl()
    {
        return $this->getConfigParameter('url');
    }
}

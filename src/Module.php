<?php
/**
 * Simpler module
 *
 * @category Agere
 * @package Agere_Simpler
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 25.07.14 15:04
 */
namespace Agere\Simpler;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}

<?php

namespace Popov\Simpler\Plugin\Factory;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SimplerPluginDelegatorFactory implements DelegatorFactoryInterface
{
    public function createDelegatorWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName, $callback )
    {
        $instance = $callback();
        if (method_exists($instance, 'setSimplerPlugin')) {
            $simplerPlugin = $serviceManager->get('ControllerPluginManager')->get('simpler');
            $instance->setSimplerPlugin($simplerPlugin);
        }
        return $instance;
    }
}
<?php

namespace Agere\Simpler\Plugin\Factory;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SimplerPluginDelegatorFactory implements DelegatorFactoryInterface
{
    public function createDelegatorWithName(ServiceLocatorInterface $serviceManager, $name, $requestedName, $callback )
    {
        $instance = $callback();
        if (method_exists($instance, 'setSimpler')) {
            $simpler = $serviceManager->get('ControllerPluginManager')->get('simpler');
            $instance->setSimpler($simpler);
        }
        return $instance;
    }
}
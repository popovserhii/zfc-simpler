<?php

namespace Popov\Simpler\Factory;

use Popov\Simpler\SimplerHelper;
use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class SimplerHelperDelegatorFactory //implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $instance = $callback();
        if (method_exists($instance, 'setSimplerHelper')) {
            $simpler = $container->get(SimplerHelper::class);
            $instance->setSimplerHelper($simpler);
        }

        return $instance;
    }
}
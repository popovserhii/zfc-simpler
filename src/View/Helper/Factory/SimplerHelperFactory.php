<?php

namespace Popov\Simpler\View\Helper\Factory;

use Psr\Container\ContainerInterface;
use Popov\Simpler\View\Helper\SimplerHelper;
use Popov\Simpler\SimplerHelper as BaseSimplerHelper;

class SimplerHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $baseHelper = $container->get(BaseSimplerHelper::class);
        $userHelper = new SimplerHelper($baseHelper);

        return $userHelper;
    }
}
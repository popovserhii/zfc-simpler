<?php

namespace Popov\Simpler;

return [
    'dependencies' => [
        SimplerHelper::class => SimplerHelper::class,
    ],
    'controller_plugins' => [
        'aliases' => [
            'simpler' => Plugin\SimplerPlugin::class,
        ],
        'invokables' => [
            Plugin\SimplerPlugin::class => Plugin\SimplerPlugin::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'simpler' => View\Helper\SimplerHelper::class,
        ],
        'factories' => [
            View\Helper\SimplerHelper::class => View\Helper\Factory\SimplerHelperFactory::class,
        ],
    ],
];

<?php

namespace Popov\Simpler;

return [
    'dependencies' => [
        SimplerHelper::class => SimplerHelper::class
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
        'invokables' => [
            'simpler' => Helper\SimplerHelper::class,
        ],
    ],
];

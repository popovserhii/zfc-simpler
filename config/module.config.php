<?php
namespace Popov\Simpler;

return [
	'controller_plugins' => [
		'aliases' => [
			'simpler' => Plugin\SimplerPlugin::class,
		],
		'invokables' => [
			Plugin\SimplerPlugin::class => Plugin\SimplerPlugin::class,
		]
        /*'factories' => [
			Plugin\Simpler::class => Plugin\Factory\SimplerFactory::class,
		]*/
	],
	'view_helpers' => [
		'invokables' => [
			'simpler' => Helper\SimplerHelper::class,
		],
	],
];

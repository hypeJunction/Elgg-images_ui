<?php

return [
	'bootstrap' => \hypeJunction\ImagesUi\Bootstrap::class,

	'plugin' => [
		'dependencies' => [
			'images' => [
				'position' => 'after',
			],
		],
	],

	'actions' => [
		'images/upload' => [],
		'images/crop' => [],
		'images/thumbs' => [],
	],

	'routes' => [
		'collection:object:image' => [
			'path' => '/images/all',
			'resource' => 'images/all',
		],
		'collection:object:image:owner' => [
			'path' => '/images/owner/{username}',
			'resource' => 'images/all',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:image:friends' => [
			'path' => '/images/friends/{username}',
			'resource' => 'images/friends',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'collection:object:image:groups' => [
			'path' => '/images/groups/{username}',
			'resource' => 'images/groups',
			'middleware' => [
				\Elgg\Router\Middleware\UserPageOwnerGatekeeper::class,
			],
		],
		'add:object:image' => [
			'path' => '/images/upload/{container_guid}',
			'resource' => 'images/upload',
		],
		'add:object:image:default' => [
			'path' => '/images/upload',
			'resource' => 'images/upload',
		],
		'edit:object:image' => [
			'path' => '/images/edit/{guid}',
			'resource' => 'images/edit',
		],
		'view:object:image' => [
			'path' => '/images/view/{guid}',
			'resource' => 'images/view',
		],
	],
];

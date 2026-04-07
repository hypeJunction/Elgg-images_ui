<?php

return [
	'bootstrap' => \hypeJunction\ImagesUi\Bootstrap::class,

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
		],
		'collection:object:image:friends' => [
			'path' => '/images/friends/{username}',
			'resource' => 'images/friends',
		],
		'collection:object:image:groups' => [
			'path' => '/images/groups/{username}',
			'resource' => 'images/groups',
		],
		'add:object:image' => [
			'path' => '/images/upload/{container_guid?}',
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

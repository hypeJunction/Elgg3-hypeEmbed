<?php

$dataroot = elgg_get_config('dataroot');
$static_asset_path = \Elgg\Project\Paths::sanitize($dataroot . 'embed/');

if (!is_dir($static_asset_path)) {
	mkdir($static_asset_path, 0700, true);
}

return [
	'actions' => [
		'embed/file/upload' => [],
		'embed/buttons' => [
			'access' => 'admin',
		],
		'embed/code' => [
			'access' => 'admin',
		]
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'ckeditor_file',
			'class' => \hypeJunction\Embed\File::class,
		],
		[
			'type' => 'object',
			'subtype' => 'embed_file',
			'class' => \hypeJunction\Embed\File::class,
		],
		[
			'type' => 'object',
			'subtype' => 'embed_code',
			'class' => \hypeJunction\Embed\EmbedCode::class,
		]
	],
	'routes' => [
		'collection:object:file:embed' => [
			'path' => 'file/embed/{guid}',
			'resource' => 'collection/group'
		],
		'collection:object:all:embed' => [
			'path' => 'posts/embed/{guid}',
			'resource' => 'collection/group'
		],
		'ckeditor:image' => [
			'path' => '/ckeditor/image/{hash}',
			'resource' => 'embed/ckeditor/image',
			'walled' => false,
		],
		'ckeditor:asset' => [
			'path' => '/ckeditor/assets/{view}',
			'resource' => 'embed/asset/view',
			'requirements' => [
				'view' => '.+',
			],
			'walled' => false,
		],
		'embed:asset' => [
			'path' => '/embed/asset/{view}',
			'resource' => 'embed/asset/view',
			'requirements' => [
				'view' => '.+',
			],
			'walled' => false,
		],
	],
	'views' => [
		'default' => [
			'embed/' => $static_asset_path,
		],
	]
];

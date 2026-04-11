<?php

$dataroot = elgg_get_config('dataroot');
$static_asset_path = \Elgg\Project\Paths::sanitize($dataroot . 'embed/');

if (!is_dir($static_asset_path)) {
	mkdir($static_asset_path, 0700, true);
}

return [
	'bootstrap' => \hypeJunction\Embed\Bootstrap::class,
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
	],
	'hooks' => [
		'register' => [
			'menu:embed' => [
				\hypeJunction\Embed\EmbedMenu::class => [],
			],
			'menu:embed:entity' => [
				\hypeJunction\Embed\EntityEmbedMenu::class => [],
			],
			'menu:longtext' => [
				\hypeJunction\Embed\LongtextMenu::class => ['priority' => 9999],
			],
		],
		'entity:icon:sizes' => [
			'object' => [
				\hypeJunction\Embed\Uploads::class . '::setIconSizes' => [],
			],
		],
		'entity:icon:file' => [
			'object' => [
				\hypeJunction\Embed\Uploads::class . '::setIconFile' => [],
			],
		],
		'layout' => [
			'page' => [
				\hypeJunction\Embed\Views::class . '::filterLightboxLayout' => [],
			],
		],
		'shell' => [
			'page' => [
				\hypeJunction\Embed\Views::class . '::filterLightboxShell' => [],
			],
		],
	],
	'view_extensions' => [
		'forms/file/upload' => [
			'embed/forms/upload' => ['priority' => 100],
		],
		'elgg.css' => [
			'embed/stylesheet.css' => [],
		],
		'admin.css' => [
			'embed/stylesheet.css' => [],
		],
	],
];

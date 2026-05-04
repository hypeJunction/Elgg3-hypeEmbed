<?php

return [
	'bootstrap' => \hypeJunction\Embed\Bootstrap::class,
	'actions' => [
		'embed/file/upload' => [],
		'embed/player' => [],
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
		'embed:tab' => [
			'path' => '/embed/{tab}',
			'resource' => 'embed/tab',
			'requirements' => [
				'tab' => '(?!asset)[a-zA-Z_]+',
			],
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
			'embed/' => \Elgg\Project\Paths::sanitize(elgg_get_config('dataroot') . 'embed/'),
		],
	],
	'events' => [
		'register' => [
			'menu:embed' => [
				\hypeJunction\Embed\EmbedMenu::class => [],
			],
			'menu:embed:entity' => [
				\hypeJunction\Embed\EntityEmbedMenu::class => [],
			],
			// Toolbar extends input/longtext directly, replacing the longtext menu approach
			// LongtextMenu class retained for backward compatibility if needed
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
		'input/longtext' => [
			'embed/toolbar' => [],
		],
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

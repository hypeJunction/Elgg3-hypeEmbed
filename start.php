<?php

/**
 * Improved embedding experience
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */
require __DIR__ . '/autoloader.php';

use hypeJunction\Embed\Uploads;
use hypeJunction\Embed\Views;

return function () {
	elgg_register_event_handler('init', 'system', function () {

		elgg_register_plugin_hook_handler('register', 'menu:embed', \hypeJunction\Embed\EmbedMenu::class);
		elgg_register_plugin_hook_handler('register', 'menu:embed:entity', \hypeJunction\Embed\EntityEmbedMenu::class);

		elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');
		elgg_register_plugin_hook_handler('register', 'menu:longtext', \hypeJunction\Embed\LongtextMenu::class, 9999);

		elgg_register_ajax_view('embed/safe/entity');

		elgg_extend_view('forms/file/upload', 'embed/forms/upload', 100);

		elgg_register_plugin_hook_handler('entity:icon:sizes', 'object', [Uploads::class, 'setIconSizes']);
		elgg_register_plugin_hook_handler('entity:icon:file', 'object', [Uploads::class, 'setIconFile']);

		elgg_extend_view('elgg.css', 'embed/stylesheet.css');
		elgg_extend_view('admin.css', 'embed/stylesheet.css');

		elgg_register_plugin_hook_handler('layout', 'page', [Views::class, 'filterLightboxLayout']);
		elgg_register_plugin_hook_handler('shell', 'page', [Views::class, 'filterLightboxShell']);

		elgg_register_collection('collection:object:file:embed', \hypeJunction\Embed\FileCollection::class);
		elgg_register_collection('collection:object:all:embed', \hypeJunction\Embed\PostCollection::class);

		if (elgg()->has('shortcodes')) {
			$svc = elgg()->shortcodes;
			/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */

			$svc->register('embed');
			$svc->register('button');
			$svc->register('code');
		}

	});
};
<?php

namespace hypeJunction\Embed;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	public function boot() {
		$dataroot = elgg()->config->dataroot;
		$staticPath = \Elgg\Project\Paths::sanitize($dataroot . 'embed/');
		if (!is_dir($staticPath)) {
			mkdir($staticPath, 0700, true);
		}
	}

	public function init() {
		\elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');

		\elgg_register_ajax_view('embed/safe/entity');

		if (function_exists('elgg_register_collection')) {
			\elgg_register_collection('collection:object:file:embed', FileCollection::class);
			\elgg_register_collection('collection:object:all:embed', PostCollection::class);
		}

		if (\elgg()->has('shortcodes')) {
			$svc = \elgg()->shortcodes;
			/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */

			$svc->register('embed');
			$svc->register('button');
			$svc->register('code');
		}
	}
}

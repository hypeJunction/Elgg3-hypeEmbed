<?php

namespace hypeJunction\Embed;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	public function init() {
		\elgg_unregister_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');

		\elgg_register_ajax_view('embed/safe/entity');

		\elgg_register_collection('collection:object:file:embed', FileCollection::class);
		\elgg_register_collection('collection:object:all:embed', PostCollection::class);

		if (\elgg()->has('shortcodes')) {
			$svc = \elgg()->shortcodes;
			/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */

			$svc->register('embed');
			$svc->register('button');
			$svc->register('code');
		}
	}
}

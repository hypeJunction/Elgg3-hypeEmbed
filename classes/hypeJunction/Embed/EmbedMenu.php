<?php

namespace hypeJunction\Embed;

use Elgg\Hook;
use ElggMenuItem;

class EmbedMenu {

	/**
	 * Setup embed menu
	 *
	 * @param Hook $hook Hook
	 *
	 * @return ElggMenuItem[]
	 */
	public function __invoke(Hook $hook) {

		$menu = $hook->getValue();

		$menu[] = ElggMenuItem::factory([
			'name' => 'posts',
			'text' => \elgg_echo('embed:posts'),
			'priority' => 300,
			'data' => [
				'view' => 'embed/tab/posts',
			],
		]);

		$menu[] = ElggMenuItem::factory([
			'name' => 'player',
			'text' => \elgg_echo('embed:player'),
			'priority' => 500,
			'data' => [
				'view' => 'embed/tab/player',
			],
		]);

		if (\elgg_is_admin_logged_in()) {
			$menu[] = ElggMenuItem::factory([
				'name' => 'assets',
				'text' => \elgg_echo('embed:assets'),
				'priority' => 900,
				'data' => [
					'view' => 'embed/tab/assets',
				],
			]);

			$menu[] = ElggMenuItem::factory([
				'name' => 'buttons',
				'text' => \elgg_echo('embed:buttons'),
				'priority' => 950,
				'data' => [
					'view' => 'embed/tab/buttons',
				],
			]);

			$menu[] = ElggMenuItem::factory([
				'name' => 'code',
				'text' => \elgg_echo('embed:code'),
				'priority' => 950,
				'data' => [
					'view' => 'embed/tab/code',
				],
			]);
		}

		$page_owner = \elgg_get_page_owner_entity();
		$id = $hook->getParam('textarea_id');

		foreach ($menu as $item) {
			if (!$item instanceof ElggMenuItem) {
				continue;
			}

			if ($item->getName() == 'file') {
				$item->setData('type', null);
				$item->setData('subtype', null);
				$item->setData('view', 'embed/tab/file');
			}

			$url = "embed/{$item->getName()}";

			if ($page_owner instanceof \ElggGroup && $page_owner->isMember()) {
				$url = \elgg_http_add_url_query_elements($url, [
					'container_guid' => $page_owner->guid,
				]);
			}

			$item->setHref('javascript:');
			$item->{'data-href'} = \elgg_normalize_url($url);

			if ($id) {
				$item->rel = "embed-lightbox-{$id}";
				$item->setLinkClass("embed-control embed-control-{$id}");
			}
		}

		return $menu;
	}

}

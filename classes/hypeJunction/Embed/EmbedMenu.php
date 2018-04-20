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
			'text' => elgg_echo('embed:posts'),
			'priority' => 300,
			'data' => [
				'view' => 'embed/tab/posts',
			],
		]);

		if (elgg_is_admin_logged_in()) {
			$menu[] = ElggMenuItem::factory([
				'name' => 'assets',
				'text' => elgg_echo('embed:assets'),
				'priority' => 900,
				'data' => [
					'view' => 'embed/tab/assets',
				],
			]);

			$menu[] = ElggMenuItem::factory([
				'name' => 'buttons',
				'text' => elgg_echo('embed:buttons'),
				'priority' => 950,
				'data' => [
					'view' => 'embed/tab/buttons',
				],
			]);

			$menu[] = ElggMenuItem::factory([
				'name' => 'code',
				'text' => elgg_echo('embed:code'),
				'priority' => 950,
				'data' => [
					'view' => 'embed/tab/code',
				],
			]);
		}

		$page_owner = elgg_get_page_owner_entity();

		foreach ($menu as $item) {
			if (!$item instanceof ElggMenuItem) {
				continue;
			}

			if ($item->getName() == 'file') {
				$item->setData('type', null);
				$item->setData('subtype', null);
				$item->setData('view', 'embed/tab/file');
			}

			$href = elgg_http_add_url_query_elements($item->getHref(), [
				'container_guid' => $page_owner->guid,
			]);

			$item->setHref($href);
		}

		return $menu;
	}

}

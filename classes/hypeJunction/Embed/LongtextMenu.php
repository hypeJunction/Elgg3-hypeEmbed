<?php


namespace hypeJunction\Embed;

use Elgg\Hook;
use ElggMenuItem;

class LongtextMenu {

	/**
	 * Add the embed menu item to the long text menu
	 *
	 * @param Hook $hook Hook
	 *
	 * @return ElggMenuItem[]|null
	 */
	public function __invoke(Hook $hook) {

		if (!elgg_is_logged_in()) {
			return null;
		}

		$items = $hook->getValue();

		if (elgg_get_context() == 'embed') {
			return null;
		}

		$id = $hook->getParam('textarea_id');
		if ($id === null) {
			return null;
		}

		$items[] = ElggMenuItem::factory([
			'name' => 'embed',
			'href' => 'javascript:',
			'text' => elgg_echo('embed:media'),
			'priority' => 10,
			'child_menu' => [
				'display' => 'dropdown',
				'class' => 'elgg-menu-hover',
				'data-position' => json_encode([
					'at' => 'right bottom',
					'my' => 'right top+8px',
					'collision' => 'fit fit',
				]),
				'id' => 'embed-menu',
			],
		]);

		$menu = elgg()->menus->getMenu('embed', $hook->getParams());
		$section = $menu->getSection('default');

		foreach ($section as $item) {
			/* @var $item ElggMenuItem */

			$item->setParentName('embed');
			$item->rel = "embed-lightbox-{$id}";
			$item->setLinkClass("elgg-lightbox embed-control embed-control-{$id} elgg-lightbox");
			$item->addDeps(['elgg/embed']);

			$url = "embed/{$item->getName()}";

			$page_owner = elgg_get_page_owner_entity();
			if ($page_owner instanceof \ElggGroup && $page_owner->isMember()) {
				$url = elgg_http_add_url_query_elements($url, [
					'container_guid' => $page_owner->guid,
				]);
			}

			$item->setHref('javascript:');
			$item->{'data-colorbox-opts'} = json_encode([
				'href' => elgg_normalize_url($url),
				'width' => '800px',
			]);

			$items[] = $item;
		}

		return $items;
	}

}
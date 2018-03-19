<?php

namespace hypeJunction\Embed;

use Elgg\Hook;

class EntityEmbedMenu {

	/**
	 * Setup entity embed menu
	 *
	 * @param Hook $hook Hook
	 *
	 * @return \ElggMenuItem[]
	 */
	public function __invoke(Hook $hook) {
		$menu = $hook->getValue();

		$entity = $hook->getEntityParam();
		if (!$entity) {
			return;
		}

		$menu[] = \ElggMenuItem::factory([
			'name' => 'embed:card',
			'class' => 'embed-insert-async',
			'text' => elgg_echo('embed:entity:card'),
			'href' => 'javascript:',
			'data-guid' => $entity->guid,
			'data-view' => 'embed/safe/entity',
			'data-format' => 'card',
		]);

		if ($entity->getIconURL('small')) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'embed:thumb',
				'class' => 'embed-insert-async',
				'text' => elgg_echo('embed:entity:thumbnail'),
				'href' => 'javascript:',
				'data-guid' => $entity->guid,
				'data-view' => 'embed/safe/entity',
				'data-format' => 'thumbnail',
			]);
		}

		if ($entity->getIconURL('master')) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'embed:icon',
				'class' => 'embed-insert-async',
				'text' => elgg_echo('embed:entity:icon'),
				'href' => 'javascript:',
				'data-guid' => $entity->guid,
				'data-view' => 'embed/safe/entity',
				'data-format' => 'icon',
			]);
		}

		if ($entity instanceof \ElggFile && $entity->getSimpleType() !== 'image') {
			$mime = $entity->getMimeType();
			$base_type = substr($mime, 0, strpos($mime, '/'));

			if (elgg_view_exists("file/specialcontent/$mime") || elgg_view_exists("file/specialcontent/$base_type/default")) {
				$menu[] = \ElggMenuItem::factory([
					'name' => 'embed:player',
					'class' => 'embed-insert-async',
					'text' => elgg_echo('embed:entity:player'),
					'href' => 'javascript:',
					'data-guid' => $entity->guid,
					'data-view' => 'embed/safe/entity',
					'data-format' => 'player',
				]);
			}
		}

		return $menu;
	}
}
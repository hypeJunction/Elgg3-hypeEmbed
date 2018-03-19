<?php
/**
 * Layout of embed panel loaded in lightbox
 */

$title =  elgg_view_title(elgg_echo('embed:media'));

$selected = elgg_get_config('embed_tab');
if (!$selected) {
	$tab = elgg_extract('tab', $vars);

	$menu = elgg()->menus->getMenu('embed');
	$sections = $menu->getSections();

	foreach ($sections as $section => $items) {
		foreach ($items as $item) {
			if ($item->getName() === $tab) {
				$selected = $item;
				break;
			}
		}
	}
}

if (!$selected) {
	throw new \Elgg\PageNotFoundException();
}

if ($selected->getData('view')) {
	$tab = elgg_view($selected->getData('view'), $vars);
} else {
	$tab = elgg_list_entities(
		embed_get_list_options($selected->getData('options')),
		'elgg_get_entities',
		'embed_list_items'
	);
}

$tab .= elgg_view('graphics/ajax_loader', [
	'class' => 'embed-throbber mtl',
]);

$container_info = elgg_view('input/hidden', [
	'name' => 'embed_container_guid',
	'value' => elgg_get_page_owner_guid(),
]);

echo elgg_view_module('aside', elgg_echo('embed:title', [$selected->getText()]), $tab, [
	'footer' => $container_info,
]);

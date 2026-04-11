<?php
/**
 * Embed tab resource view
 *
 * Renders the embed layout for a given tab, served via AJAX
 * for the toolbar popup or lightbox.
 */

$tab = elgg_extract('tab', $vars);

$container_guid = (int) get_input('container_guid');
if ($container_guid) {
	elgg_set_page_owner_guid($container_guid);
}

elgg_push_context('embed');

echo elgg_view('embed/layout', [
	'tab' => $tab,
]);

elgg_pop_context();

<?php

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	$page_owner = elgg_get_logged_in_user_entity();
}

$collection = elgg_get_collection('collection:object:all:embed', $page_owner, $vars);

$vars['collection'] = $collection;
$vars['expand_form'] = false;

echo elgg_view('collection/search', $vars);
echo elgg_view('collection/view', $vars);
<?php
$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggEntity) {
	return;
}

$title = $entity->getDisplayName();

if ($entity->hasIcon('small') || $entity instanceof ElggFile) {
	$icon = elgg_view('output/img', [
		'src' => $entity->getIconURL('small'),
		'alt' => $title,
	]);
} else {
	$owner = $entity->getOwnerEntity();
	$icon = elgg_view('output/img', [
		'src' => $owner->getIconURL('small'),
		'alt' => $title,
	]);
}

$subtitle = elgg_format_element('span', [
	'class' => 'embed-type-badge elgg-badge mrs',
		], elgg_echo("item:object:{$entity->subtype}"));

$subtitle .= elgg_view('object/elements/imprint', $vars);

$menu = elgg_view_menu('embed:entity', [
	'entity' => $entity,
	'class' => 'elgg-menu-hz',
		]);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'title' => $title,
	'subtitle' => $subtitle,
	'content' => $menu,
	'metadata' => false,
	'tags' => false,
	'icon' => $icon,
    'class' => 'embed-item-summary',
]);
?>
<script>
	require(['embed/lists/item']);
</script>


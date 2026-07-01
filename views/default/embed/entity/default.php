<?php

$entity = elgg_extract('entity', $vars);

$type = $entity->getType();
$subtype = $entity->getSubtype();

$subtypes = elgg_entity_types_with_capability('searchable')[$type] ?? [];
if ($subtype && !in_array($subtype, $subtypes)) {
	return;
}

echo elgg_view_entity($entity, [
	'full_view' => false,
	'size' => elgg_extract('size', $vars),
	'metadata' => false,
]);

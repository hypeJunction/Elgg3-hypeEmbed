<?php

$svc = elgg()->shortcodes;
/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */


$entity = elgg_extract('entity', $vars);
if (!elgg_instanceof($entity)) {
	return;
}

$format = elgg_extract('format', $vars, 'card');

switch ($format) {
	default :
	case 'card' :
	case 'player' :
		$attrs = [
			'title' => elgg_get_excerpt($entity->getDisplayName()),
			'url' => $entity->getURL(),
			'guid' => $entity->guid,
			'format' => $format,
		];

		$output = $svc->generate('embed', $attrs);
		$output = elgg_format_element('div', [
			'contenteditable' => 'false',
		], $output);
		break;

	case 'icon' :
		$output = elgg_view('output/img', [
			'src' => elgg_get_embed_url($entity, 'master'),
			'alt' => $entity->getDisplayName(),
		]);
		$output = elgg_view('output/url', [
			'href' => $entity->getURL(),
			'text' => $output,
		]);
		break;

	case 'thumbnail' :
		$output = elgg_view('output/img', [
			'src' => elgg_get_embed_url($entity, 'small'),
			'alt' => $entity->getDisplayName(),
		]);
		$output = elgg_view('output/url', [
			'href' => $entity->getURL(),
			'text' => $output,
		]);
		break;

}

echo elgg_trigger_plugin_hook('prepare:entity', 'embed', $vars, $output);

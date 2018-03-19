<?php

$svc = elgg()->shortcodes;
/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */

$text = elgg_extract('text', $vars);
$type = elgg_extract('type', $vars, 'action');
$url = elgg_extract('url', $vars);
$target = elgg_extract('target', $vars);

$attrs = [
	'url' => $url,
	'type' => $type,
	'target' => $target,
	'text' => $text,
];

$output = $svc->generate('button', $attrs);

echo elgg_trigger_plugin_hook('prepare:button', 'embed', $vars, $output);

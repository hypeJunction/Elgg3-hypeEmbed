<?php

$url = elgg_extract('url', $vars);

if (!$url) {
	return;
}

$attrs = [
	'url' => $url,
];

if (elgg()->has('shortcodes')) {
	$svc = elgg()->shortcodes;
	/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */

	$output = '<p>' . $svc->getShortcodeTag('player', $attrs) . '</p>';
} else {
	$output = elgg_view('output/url', [
		'href' => $url,
		'text' => $url,
	]);
}

echo elgg_trigger_plugin_hook('prepare:player', 'embed', $vars, $output);

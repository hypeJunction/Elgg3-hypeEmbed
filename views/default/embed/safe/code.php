<?php

$svc = elgg()->shortcodes;
/* @var $svc \hypeJunction\Shortcodes\ShortcodesService */


$user_guid = elgg_extract('user_guid', $vars);
$token = elgg_extract('token', $vars);

$attrs = [
	'id' => "$user_guid:$token",
];

$output = $svc->generate('code', $attrs);

echo elgg_trigger_plugin_hook('prepare:code', 'embed', $vars, $output);

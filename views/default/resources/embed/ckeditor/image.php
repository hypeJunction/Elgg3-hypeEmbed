<?php

$hash = elgg_extract('hash', $vars);

if (!$hash) {
	throw new \Elgg\Exceptions\Http\PageNotFoundException();
}

$files = elgg_get_entities([
	'types' => 'object',
	'subtypes' => 'embed_file',
	'limit' => 1,
	'metadata_name_value_pairs' => [
		'hash' => $hash,
	],
]);

if (!$files) {
	throw new \Elgg\Exceptions\Http\PageNotFoundException();
}

$file = array_shift($files);

return elgg_redirect_response(elgg_get_embed_url($file, 'large'));

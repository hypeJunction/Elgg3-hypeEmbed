<?php

elgg_ajax_gatekeeper();

$upload_type = get_input('embed_upload_type', 'file');

$class = elgg_get_entity_class('object', $upload_type);

// Get variables
$title = elgg_get_title_input();
$desc = get_input('description');
$access_id = (int) get_input('access_id');
$container_guid = (int) get_input('container_guid', 0);
$tags = get_input('tags');

$container_guid = $container_guid ? : elgg_get_logged_in_user_guid();

// check if upload attempted and failed
$uploaded_file = elgg_get_uploaded_file('upload', false);
if (!$uploaded_file || !$uploaded_file->isValid()) {
	$error = elgg_get_friendly_upload_error($uploaded_file->getError());

	return elgg_error_response($error);
}

$file = new $class();

if ($title) {
	$file->title = $title;
}
$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->tags = string_to_tag_array($tags);

$file->save();

// save master file
if (!$file->acceptUploadedFile($uploaded_file)) {
	return elgg_error_response(elgg_echo('file:uploadfailed'));
}

if (!$file->save()) {
	return elgg_error_response(elgg_echo('file:uploadfailed'));
}

// update icons
$file->saveIconFromElggFile($file);

$forward = $file->getURL();

// handle results differently for new files and file updates

$container = get_entity($container_guid);
if ($container instanceof ElggGroup) {
	$forward_url = elgg_generate_url('collection:object:file:group', ['guid' => $container->guid]);
} else {
	$forward_url = elgg_generate_url('collection:object:file:owner', ['username' => $container->username]);
}

if ($file->subtype === 'file') {
	elgg_create_river_item([
		'action_type' => 'create',
		'object_guid' => $file->guid,
	]);
}

$output = elgg_view('embed/safe/entity', [
	'entity' => $file,
	'format' => $file->getSimpleType() === 'image' ? 'image' : 'card',
]);

return elgg_ok_response($output, elgg_echo('file:saved'), $forward);

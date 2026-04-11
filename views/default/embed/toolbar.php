<?php
/**
 * Embed toolbar rendered below longtext inputs
 *
 * @uses $vars['embeds'] Disable embed by setting to false
 * @uses $vars['id']     Textarea ID
 */

if (!elgg_is_logged_in()) {
	return;
}

if (elgg_get_context() === 'embed') {
	return;
}

if (!elgg_extract('embeds', $vars, true)) {
	return;
}

$id = elgg_extract('id', $vars);
if (!$id) {
	return;
}

?>
<div class="embed-toolbar" data-textarea-id="<?= elgg_format_element('', [], $id) ?>">
	<?php
	echo elgg_view_menu('embed', [
		'sort_by' => 'priority',
		'textarea_id' => $id,
	]);

	echo elgg_view_menu('longtext', [
		'sort_by' => 'priority',
		'class' => 'elgg-menu-embed',
		'id' => $id,
	]);
	?>
	<div class="elgg-module-popup embed-toolbar-popup"></div>
</div>
<?php

elgg_require_js('embed/toolbar');

<?php
/**
 * Upload a file through the embed interface
 */

echo elgg_view_form('file/upload', [
	'enctype' => 'multipart/form-data',
    'class' => 'elgg-form-embed-file-upload',
    'action' => 'action/embed/file/upload',
], [
	'container_guid' => elgg_get_page_owner_guid(),
]);

?>
<script>
	require(['embed/file_upload/content']);
</script>

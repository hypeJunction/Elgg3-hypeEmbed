<?php

echo elgg_view_form('embed/buttons', [
	'class' => 'elgg-form-embed-buttons',
		], $vars);
?>
<script>
	require(['embed/tab/buttons']);
</script>

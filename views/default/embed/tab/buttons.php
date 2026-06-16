<?php

echo elgg_view_form('embed/buttons', [
	'class' => 'elgg-form-embed-buttons',
], $vars);

elgg_import_esm('embed/tab/buttons');

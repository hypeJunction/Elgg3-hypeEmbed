<?php

$view = (array) elgg_extract('view', $vars, []);

$view = implode('/', $view);

return elgg_redirect_response(elgg_get_simplecache_url("embed/$view"));
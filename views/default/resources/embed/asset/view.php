<?php

$view = (array) elgg_extract('view', $vars, []);

$view = implode('/', $view);

forward(elgg_get_simplecache_url("embed/$view"));
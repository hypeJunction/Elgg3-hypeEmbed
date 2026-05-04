<?php

namespace hypeJunction\Embed;

/**
 * Handles public page route registration for walled garden.
 */
class Router {

	/**
	 * Set public pages
	 *
	 * @param string $hook   "public_pages"
	 * @param string $type   "walled_garden"
	 * @param array  $return Public pages
	 *
	 * @return array
	 */
	public static function setPublicPages($hook, $type, $return) {

		$return[] = 'ckeditor/.*';
		$return[] = 'embed/.*';

		return $return;
	}
}

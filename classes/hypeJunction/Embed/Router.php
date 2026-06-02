<?php

namespace hypeJunction\Embed;

/**
 * Handles public page route registration for walled garden.
 */
class Router {

	/**
	 * Set public pages
	 *
	 * @param \Elgg\Event $hook   "public_pages" event
	 * @param string      $type   "walled_garden"
	 * @param array       $return Public pages
	 *
	 * @return array
	 */
	public static function setPublicPages($hook, $type = null, $return = null) {

		if ($hook instanceof \Elgg\Hook || $hook instanceof \Elgg\Event) {
			$type = $hook->getType();
			$return = $hook->getValue();
		}

		$return[] = 'ckeditor/.*';
		$return[] = 'embed/.*';

		return $return;
	}
}

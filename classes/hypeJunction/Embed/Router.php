<?php

namespace hypeJunction\Embed;

/**
 * Handles public page route registration for walled garden.
 */
class Router {

	/**
	 * Set public pages.
	 *
	 * @param \Elgg\Event $event public_pages:walled_garden event
	 * @return array
	 */
	public static function setPublicPages(\Elgg\Event $event) {
		$return = $event->getValue();
		$return[] = 'ckeditor/.*';
		$return[] = 'embed/.*';
		return $return;
	}
}

<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Jun-17
 * Time: 17:22
 */

namespace hypeJunction\Embed;

/**
 * View filter handlers for the embed lightbox layout.
 */
class Views {

	/**
	 * Replace layout for embedded lightbox pages
	 *
	 * @param \Elgg\Event $hook Hook object
	 *
	 * @return string|null
	 */
	public static function filterLightboxLayout(\Elgg\Event $hook = null) {
		if (get_input('embed_lightbox')) {
			return 'embed_lightbox';
		}
	}

	/**
	 * Replace shell for embedded lightbox pages
	 *
	 * @param \Elgg\Event $hook Hook object
	 *
	 * @return string|null
	 */
	public static function filterLightboxShell(\Elgg\Event $hook = null) {
		if (get_input('embed_lightbox')) {
			return 'embed_lightbox';
		}
	}
}

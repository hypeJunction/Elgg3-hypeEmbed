<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26-Jun-17
 * Time: 17:22
 */

namespace hypeJunction\Embed;


class Views {

	/**
	 * Replace layout for embedded lightbox pages
	 *
	 * @param \Elgg\Hook $hook Hook object
	 *
	 * @return string|null
	 */
	public static function filterLightboxLayout(\Elgg\Hook $hook = null) {
		if (get_input('embed_lightbox')) {
			return 'embed_lightbox';
		}
	}

	/**
	 * Replace shell for embedded lightbox pages
	 *
	 * @param \Elgg\Hook $hook Hook object
	 *
	 * @return string|null
	 */
	public static function filterLightboxShell(\Elgg\Hook $hook = null) {
		if (get_input('embed_lightbox')) {
			return 'embed_lightbox';
		}
	}
}

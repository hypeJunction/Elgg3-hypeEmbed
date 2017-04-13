<?php

namespace hypeJunction\Embed;

/**
 * @access private
 */
class Shortcodes {

	static $shortcodes = [
		'embed',
		'player',
	];

	/**
	 * Expand shortcodes
	 *
	 * @param string $value Text
	 * @return string
	 */
	public static function expandShortcodes($value) {

		$shortcodes = implode('|', self::$shortcodes);

		return preg_replace_callback("/\[({$shortcodes})(.*)\]/", function($matches) {
			$full = $matches[0];

			$shortcode = $matches[1];

			if (!elgg_view_exists("embed/shortcode/$shortcode")) {
				return $full;
			}

			preg_match_all('/(\s+)([a-z0-9]+)(\=\"(.*?)\")?/', $matches[2], $attribute_matches);

			$attributes = [];
			for ($i = 0; $i < count($attribute_matches[0]); $i++) {
				$key = filter_tags($attribute_matches[2][$i]);
				$value = filter_tags($attribute_matches[4][$i]);
				if (strpos($value, 'x_') === 0) {
					$value = substr($value, 2);
					$value = base64_decode($value);
				}
				$value = htmlspecialchars_decode($value, ENT_QUOTES);
				$attributes[$key] = $value;
			}
			
			return elgg_view("embed/shortcode/$shortcode", $attributes);
		}, $value);
	}

	/**
	 * Strip shortcodes
	 *
	 * @param string $value
	 */
	public static function stripShortcodes($value) {

		$shortcodes = implode('|', self::$shortcodes);
		return preg_replace_callback("/\[({$shortcodes})(.*?)\]/", function($matches) {
			return '';
		}, $value);
	}

	/**
	 * Prepares a shortcode tag
	 *
	 * @param string $shortcode Shortcode name
	 * @param array  $attrs     Attributes
	 * @return string
	 */
	public static function getShortcodeTag($shortcode, array $attrs = []) {
		foreach ($attrs as &$value) {
			if (strpos($value, '[') !== false || strpos($value, ']') !== false) {
				$value = 'x_' . base64_encode($value);
			}
		}

		$attributes = elgg_format_attributes($attrs);
		return "[$shortcode $attributes]";
	}

	/**
	 * Expand shortcodes
	 *
	 * @param string $hook "view_vars"
	 * @param string $type "output/longtext"
	 * @param array  $vars View vars
	 * @return array
	 */
	public static function filterLongtextOutputVars($hook, $type, $vars) {

		$value = elgg_extract('value', $vars, '');
		$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

		$shortcodes = implode('|', self::$shortcodes);

		if (!preg_match_all("/\[({$shortcodes}).*?\]/", $value)) {
			return;
		}

		$parse_urls = elgg_extract('parse_urls', $vars, true);
		$vars['parse_urls'] = false;

		$sanitize = elgg_extract('sanitize', $vars, true);
		$vars['sanitize'] = false;

		if ($parse_urls) {
			// parse urls excluding shortcode tags
			$callback = [__CLASS__, 'parseUrls'];
			$regex = "/<a[^>]*?>.*?<\/a>|<.*?>|\[$shortcodes.*?\]|(^|\s|\!|\.|\?|>|\G)+(h?[t|f]??tps*:\/\/[^\s\r\n\t<>\"\'\)\(]+)/i";
			$value = preg_replace_callback($regex, $callback, $value);
		}

		if ($sanitize) {
			$value = filter_tags($value);
		}

		$value = self::expandShortcodes($value);

		$vars['value'] = $value;
		return $vars;
	}

	/**
	 * Implement custom URL parsing to avoid rewriting ECML tags
	 *
	 * @param string $hook "view_vars"
	 * @param string $type ECML view
	 * @param array  $vars View vars
	 * @return array
	 */
	public static function filterExcerptVars($hook, $type, $vars) {

		$value = elgg_extract('text', $vars, '');
		$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

		$value = self::stripShortcodes($value);

		$vars['text'] = $value;
		return $vars;
	}

	/**
	 * Callback function for url preg_replace_callback
	 *
	 * @param array $matches An array of matches
	 * @return string
	 */
	protected static function parseUrls($matches) {
		if (empty($matches[2])) {
			return $matches[0];
		}

		$text = $matches[2];
		return $matches[1] . elgg_format_element('a', array(
					'href' => $matches[2],
					'rel' => 'nofollow',
						), $text);
	}

}
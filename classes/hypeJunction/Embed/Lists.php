<?php

namespace hypeJunction\Embed;

/**
 * @access private
 */
class Lists {
	
	/**
	 * Add file simpletype filter options
	 *
	 * @param \Elgg\Event $hook   "filter_options" event
	 * @param string      $type   "object"
	 * @param array       $return Options
	 * @param array       $params Event params
	 * @return array
	 */
	public static function addFileSimpletypeOptions($hook, $type = null, $return = null, $params = null) {

		if ($hook instanceof \Elgg\Hook || $hook instanceof \Elgg\Event) {
			$type = $hook->getType();
			$return = $hook->getValue();
			$params = $hook->getParams();
		}

		$filter = \elgg_extract('filter', $params);
		list($prefix, $simpletype) = explode(':', $filter, 2);

		if ($prefix == 'simpletype' && $simpletype != 'all') {
			$return['metadata_name_value_pairs'][] = [
				'name' => 'simpletype',
				'value' => $simpletype,
			];
		}

		return $return;
	}
}

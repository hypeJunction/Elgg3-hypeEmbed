<?php

namespace hypeJunction\Embed;

/**
 * @access private
 */
class Lists {
	
	/**
	 * Add file simpletype filter options
	 *
	 * @param string $hook   "filter_options"
	 * @param string $type   "object"
	 * @param array  $return Options
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function addFileSimpletypeOptions(\Elgg\Hook $hook) {
		$return = $hook->getValue();


		$filter = $hook->getParam('filter');
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

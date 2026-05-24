<?php

namespace hypeJunction\Embed;

/**
 * @access private
 */
class Lists {
	
	/**
	 * Add file simpletype filter options
	 *
	 * @param \Elgg\Event $event "filter_options","object" event
	 * @return array
	 */
	public static function addFileSimpletypeOptions(\Elgg\Event $event) {
		$return = $event->getValue();
		$params = $event->getParams();

		$filter = elgg_extract('filter', $params);
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

<?php

namespace hypeJunction\Embed;

use Elgg\Database\Clauses\WhereClause;
use Elgg\Database\QueryBuilder;
use hypeJunction\Lists\FilterInterface;

class FileTypeFilter implements FilterInterface {


	/**
	 * Returns ID of the filter
	 * @return string
	 */
	public static function id() {
		return 'file_type';
	}

	/**
	 * Build a filtering clause
	 *
	 * @param       $target \ElggEntity Target entity of the filtering relationship
	 * @param array $params Filter params
	 *
	 * @return WhereClause|null
	 */
	public static function build(\ElggEntity $target = null, array $params = []) {

		$file_type = elgg_extract('file_type', $params);
		if (!$file_type) {
			return null;
		}

		$filter = function(QueryBuilder $qb, $from_alias) use ($file_type) {
			$qb->joinMetadataTable($from_alias, 'guid', 'simpletype', 'inner', 'file_type');

			return $qb->compare('file_type.value', '=', $file_type, ELGG_VALUE_STRING);
		};

		return new WhereClause($filter);
	}
}
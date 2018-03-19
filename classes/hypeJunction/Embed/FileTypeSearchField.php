<?php

namespace hypeJunction\Embed;

use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\QueryBuilder;
use hypeJunction\Lists\SearchFields\SearchField;

class FileTypeSearchField extends SearchField {

	/**
	 * Returns field name
	 * @return string
	 */
	public function getName() {
		return 'file_type';
	}

	/**
	 * Returns field parameters
	 * @return array|null
	 */
	public function getField() {

		$metadata = elgg_get_metadata([
			'types' => 'object',
			'subtypes' => ['file', 'embed_file'],
			'metadata_names' => 'simpletype',
			'group_by' => ["n_table.value"],
			'wheres' => function(QueryBuilder $qb) {
				return $qb->compare('n_table.value', '!=', '', ELGG_VALUE_STRING);
			},
			'limit' => 0,
		]);

		if (empty($metadata)) {
			return null;
		}

		$options_values = ['' => elgg_echo('sort:object:filter:simpletype:all')];

		foreach ($metadata as $md) {
			$options_values[$md->value] = elgg_echo("sort:object:filter:simpletype:{$md->value}");
		}

		return [
			'#type' => 'select',
			'#label' => elgg_echo('sort:object:filter:simpletype'),
			'name' => $this->getName(),
			'value' => $this->getValue(),
			'options_values' => $options_values,
		];
	}

	/**
	 * Set constraints on the collection based on field value
	 * @return void
	 */
	public function setConstraints() {
		$value = $this->getValue();

		if (!$value) {
			return;
		}

		$this->collection->addFilter(FileTypeFilter::class, null, ['file_type' => $value]);
	}
}
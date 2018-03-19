<?php
/**
 *
 */

namespace hypeJunction\Embed;

use hypeJunction\Lists\Collection;
use hypeJunction\Lists\Filters\SubtypeFilter;
use hypeJunction\Lists\SearchFields\Subtype;
use hypeJunction\Lists\Sorters\Alpha;
use hypeJunction\Lists\Sorters\TimeCreated;

class PostCollection extends Collection {

	/**
	 * Get ID of the collection
	 * @return string
	 */
	public function getId() {
		return "collection:object:all:embed";
	}

	/**
	 * Get title of the collection
	 * @return string
	 */
	public function getDisplayName() {
		return elgg_echo("collection:object:all:embed");
	}

	/**
	 * Get the type of collection, e.g. owner, friends, group all
	 * @return string
	 */
	public function getCollectionType() {
		return 'embed';
	}

	/**
	 * Get type of entities in the collection
	 * @return mixed
	 */
	public function getType() {
		return 'object';
	}

	/**
	 * Get subtypes of entities in the collection
	 * @return string|string[]
	 */
	public function getSubtypes() {
		$subtypes = get_registered_entity_types('object');

		return array_diff($subtypes, ['comment', 'file', 'discussion_reply']);
	}

	/**
	 * Get default query options
	 *
	 * @param array $options Query options
	 *
	 * @return array
	 */
	public function getQueryOptions(array $options = []) {
		$target = $this->getTarget();
		if ($target instanceof \ElggGroup) {
			$options['container_guids'] = $target->guid;
		} else if ($target instanceof \ElggUser) {
			$options['owner_guids'] = $target->guid;
		}

		$options['limit'] = 5;

		return $options;
	}

	/**
	 * Get default list view options
	 *
	 * @param array $options List view options
	 *
	 * @return mixed
	 */
	public function getListOptions(array $options = []) {
		return array_merge($options, [
			'no_results' => elgg_echo('embed:tab:posts:empty'),
			'item_view' => 'embed/lists/item',
			'item_class' => 'embed-list-item',
			'list_class' => 'embed-list',
			'list_type' => 'list',
		]);
	}

	/**
	 * Returns base URL of the collection
	 *
	 * @return string
	 */
	public function getURL() {
		return elgg_generate_url($this->getId(), [
			'guid' => $this->target ? $this->target->guid : '',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSortOptions() {
		return [
			Alpha::id() => Alpha::class,
			TimeCreated::id() => Alpha::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSearchOptions() {
		$options = parent::getSearchOptions();

		array_unshift($options, Subtype::class);

		return $options;
	}
}
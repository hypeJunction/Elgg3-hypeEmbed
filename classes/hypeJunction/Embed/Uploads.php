<?php

namespace hypeJunction\Embed;

/**
 * Event handlers for embed file icon management.
 */
class Uploads {

	/**
	 * Set custom icon sizes for embed_file objects.
	 *
	 * @param \Elgg\Event $hook Event
	 *
	 * @return array|null
	 */
	public static function setIconSizes(\Elgg\Event $hook) {

		$entity_subtype = $hook->getParam('entity_subtype');
		if ($entity_subtype !== 'embed_file') {
			return;
		}

		return [
			'small' => [
				'w' => 60,
				'h' => 60,
				'square' => true,
				'upscale' => true,
			],
			'medium' => [
				'w' => 153,
				'h' => 153,
				'square' => true,
				'upscale' => true,
			],
			'large' => [
				'w' => 600,
				'h' => 600,
				'upscale' => false,
			],
		];
	}

	/**
	 * Set custom file thumbnail location for embed_file objects.
	 *
	 * @param \Elgg\Event $hook Event
	 *
	 * @return \ElggIcon|null
	 */
	public static function setIconFile(\Elgg\Event $hook) {

		$entity = $hook->getParam('entity');
		$size = $hook->getParam('size', 'large');

		if (!($entity instanceof \ElggObject) || $entity->getSubtype() !== 'embed_file') {
			return;
		}

		switch ($size) {
			case 'small':
				$filename_prefix = 'thumb';
				$metadata_name = 'thumbnail';
				break;

			case 'medium':
				$filename_prefix = 'smallthumb';
				$metadata_name = 'smallthumb';
				break;

			case 'large':
				$filename_prefix = 'largethumb';
				$metadata_name = 'largethumb';
				break;

			default:
				$filename_prefix = "{$size}thumb";
				$metadata_name = $filename_prefix;
				break;
		}

		$hook->getValue()->owner_guid = $entity->owner_guid;
		if (isset($entity->$metadata_name)) {
			$hook->getValue()->setFilename($entity->$metadata_name);
		} else {
			$filename = pathinfo($entity->getFilenameOnFilestore(), PATHINFO_FILENAME);
			$filename = "file/{$filename_prefix}{$filename}.jpg";
			$hook->getValue()->setFilename($filename);
		}

		return $hook->getValue();
	}
}

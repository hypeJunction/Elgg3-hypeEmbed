<?php

namespace hypeJunction\Embed;

class Uploads {

	/**
	 * Set custom icon sizes for file objects
	 *
	 * @param string $hook   "entity:icon:url"
	 * @param string $type   "object"
	 * @param array  $return Sizes
	 * @param array  $params Hook params
	 *
	 * @return array
	 */
	public static function setIconSizes($hook, $type, $return, $params) {

		$entity_subtype = elgg_extract('entity_subtype', $params);
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
	 * Set custom file thumbnail location
	 *
	 * @param string    $hook   "entity:icon:file"
	 * @param string    $type   "object"
	 * @param \ElggIcon $icon   Icon file
	 * @param array     $params Hook params
	 *
	 * @return \ElggIcon
	 */
	public static function setIconFile($hook, $type, $icon, $params) {

		$entity = elgg_extract('entity', $params);
		$size = elgg_extract('size', $params, 'large');

		if (!elgg_instanceof($entity, 'object', 'embed_file')) {
			return;
		}

		switch ($size) {
			case 'small' :
				$filename_prefix = 'thumb';
				$metadata_name = 'thumbnail';
				break;

			case 'medium' :
				$filename_prefix = 'smallthumb';
				$metadata_name = 'smallthumb';
				break;

			case 'large' :
				$filename_prefix = 'largethumb';
				$metadata_name = 'largethumb';
				break;

			default :
				$filename_prefix = "{$size}thumb";
				$metadata_name = $filename_prefix;
				break;
		}

		$icon->owner_guid = $entity->owner_guid;
		if (isset($entity->$metadata_name)) {
			$icon->setFilename($entity->$metadata_name);
		} else {
			$filename = pathinfo($entity->getFilenameOnFilestore(), PATHINFO_FILENAME);
			$filename = "file/{$filename_prefix}{$filename}.jpg";
			$icon->setFilename($filename);
		}

		return $icon;
	}

}

<?php

namespace hypeJunction\Embed;

use Elgg\Database\Seeds\Seed;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * Seeds embed code entities for development and testing.
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		while ($this->seedsCount() < $this->getCount()) {
			$entity = new EmbedCode();
			$entity->owner_guid = $this->getRandomUser()->guid;
			$entity->container_guid = $entity->owner_guid;
			$entity->title = $this->faker->sentence(4);
			$entity->description = $this->faker->paragraph();
			$entity->embed_url = $this->faker->url();
			$entity->embed_code = '<iframe src="' . $this->faker->url() . '" width="560" height="315"></iframe>';

			if (!$entity->save()) {
				continue;
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		$entities = \elgg_get_entities([
			'type' => 'object',
			'subtype' => 'embed_code',
			'limit' => false,
			'batch' => true,
		]);

		foreach ($entities as $entity) {
			$entity->delete();
			$this->advance();
		}
	}

	/**
	 * Register this seeder with the seeds event.
	 *
	 * @param \Elgg\Event $event seeds,database event
	 * @return array
	 */
	public static function addSeed(\Elgg\Event $event) {
		$seeds = $event->getValue();
		$seeds[] = self::class;
		return $seeds;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType(): string {
		return 'embed_code';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCountOptions(): array {
		return [
			'type' => 'object',
			'subtype' => 'embed_code',
		];
	}

}

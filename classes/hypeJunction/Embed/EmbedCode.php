<?php

namespace hypeJunction\Embed;

/**
 * Represents an embed code object entity.
 */
class EmbedCode extends \ElggFile {

	const SUBTYPE = 'embed_code';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}
}

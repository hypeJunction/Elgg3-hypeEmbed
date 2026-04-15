<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Verify elgg-plugin.php entity class mapping for embed subtypes.
 */
class EntityClassMappingTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testEmbedFileClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'embed_file');
        $this->assertEquals(File::class, $class);
    }

    /**
     * @return void
     */
    public function testCkeditorFileClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'ckeditor_file');
        $this->assertEquals(File::class, $class);
    }

    /**
     * @return void
     */
    public function testEmbedCodeClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'embed_code');
        $this->assertEquals(EmbedCode::class, $class);
    }

    /**
     * @return void
     */
    public function testFileSubtypeConstant(): void {
        $this->assertEquals('embed_file', File::SUBTYPE);
    }

    /**
     * @return void
     */
    public function testEmbedCodeSubtypeConstant(): void {
        $this->assertEquals('embed_code', EmbedCode::SUBTYPE);
    }

    /**
     * @return void
     */
    public function testFileInstanceHasCorrectSubtype(): void {
        $file = new File();
        $this->assertEquals('embed_file', $file->getSubtype());
        $this->assertInstanceOf(\ElggFile::class, $file);
    }

    /**
     * @return void
     */
    public function testEmbedCodeInstanceHasCorrectSubtype(): void {
        $code = new EmbedCode();
        $this->assertEquals('embed_code', $code->getSubtype());
        $this->assertInstanceOf(\ElggFile::class, $code);
    }
}

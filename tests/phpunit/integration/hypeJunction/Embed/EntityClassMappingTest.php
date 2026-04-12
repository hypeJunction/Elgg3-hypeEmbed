<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Verify elgg-plugin.php entity class mapping for embed subtypes.
 */
class EntityClassMappingTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function testEmbedFileClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'embed_file');
        $this->assertEquals(File::class, $class);
    }

    public function testCkeditorFileClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'ckeditor_file');
        $this->assertEquals(File::class, $class);
    }

    public function testEmbedCodeClassIsMapped(): void {
        $class = elgg_get_entity_class('object', 'embed_code');
        $this->assertEquals(EmbedCode::class, $class);
    }

    public function testFileSubtypeConstant(): void {
        $this->assertEquals('embed_file', File::SUBTYPE);
    }

    public function testEmbedCodeSubtypeConstant(): void {
        $this->assertEquals('embed_code', EmbedCode::SUBTYPE);
    }

    public function testFileInstanceHasCorrectSubtype(): void {
        $file = new File();
        $this->assertEquals('embed_file', $file->getSubtype());
        $this->assertInstanceOf(\ElggFile::class, $file);
    }

    public function testEmbedCodeInstanceHasCorrectSubtype(): void {
        $code = new EmbedCode();
        $this->assertEquals('embed_code', $code->getSubtype());
        $this->assertInstanceOf(\ElggFile::class, $code);
    }
}

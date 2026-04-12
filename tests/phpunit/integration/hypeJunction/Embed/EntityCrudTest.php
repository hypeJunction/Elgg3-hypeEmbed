<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * CRUD lifecycle tests for embed_file and embed_code entities.
 */
class EntityCrudTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function testEmbedFileCanBeSavedAndLoaded(): void {
        $user = $this->createUser();
        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->title = 'Test embed file';
        $this->assertNotFalse($file->save());

        _elgg_services()->entityCache->delete($file->guid);
        $loaded = get_entity($file->guid);
        $this->assertInstanceOf(File::class, $loaded);
        $this->assertEquals('embed_file', $loaded->getSubtype());
        $this->assertEquals('Test embed file', $loaded->title);

        $file->delete();
    }

    public function testEmbedCodeCanBeSavedWithToken(): void {
        $user = $this->createUser();
        $code = new EmbedCode();
        $code->owner_guid = $user->guid;
        $code->container_guid = $user->guid;
        $code->access_id = ACCESS_PRIVATE;
        $code->token = 'test_token_abc';
        $code->setMimeType('text/html');
        $this->assertNotFalse($code->save());

        _elgg_services()->entityCache->delete($code->guid);
        $loaded = get_entity($code->guid);
        $this->assertInstanceOf(EmbedCode::class, $loaded);
        $this->assertEquals('embed_code', $loaded->getSubtype());
        $this->assertEquals('test_token_abc', $loaded->token);
        $this->assertEquals(ACCESS_PRIVATE, (int) $loaded->access_id);

        $code->delete();
    }

    public function testEmbedFileOwnerCanEdit(): void {
        $owner = $this->createUser();
        $other = $this->createUser();

        $file = new File();
        $file->owner_guid = $owner->guid;
        $file->container_guid = $owner->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->save();

        $this->assertTrue($file->canEdit($owner->guid));
        $this->assertFalse($file->canEdit($other->guid));

        $file->delete();
    }

    public function testEmbedFileCanBeDeleted(): void {
        $user = $this->createUser();
        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->save();
        $guid = $file->guid;

        $this->assertTrue($file->delete());
        $this->assertFalse(get_entity($guid));
    }
}

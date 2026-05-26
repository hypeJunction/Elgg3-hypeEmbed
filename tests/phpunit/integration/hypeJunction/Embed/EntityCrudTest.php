<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * CRUD lifecycle tests for embed_file and embed_code entities.
 */
class EntityCrudTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testEmbedFileCanBeSavedAndLoaded(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);

        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->title = 'Test embed file';
        $this->assertNotFalse($file->save());

        \_elgg_services()->session_manager->removeLoggedInUser();

        \_elgg_services()->entityCache->delete($file->guid);
        $loaded = get_entity($file->guid);
        $this->assertInstanceOf(File::class, $loaded);
        $this->assertEquals('embed_file', $loaded->getSubtype());
        $this->assertEquals('Test embed file', $loaded->title);

        $file->delete();
    }

    /**
     * @return void
     */
    public function testEmbedCodeCanBeSavedWithToken(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);

        $code = new EmbedCode();
        $code->owner_guid = $user->guid;
        $code->container_guid = $user->guid;
        $code->access_id = ACCESS_PRIVATE;
        $code->token = 'test_token_abc';
        $code->setMimeType('text/html');
        $this->assertNotFalse($code->save());

        // Keep user logged in so ACCESS_PRIVATE entity is readable
        \_elgg_services()->entityCache->delete($code->guid);
        $loaded = get_entity($code->guid);
        $this->assertInstanceOf(EmbedCode::class, $loaded);
        $this->assertEquals('embed_code', $loaded->getSubtype());
        $this->assertEquals('test_token_abc', $loaded->token);
        $this->assertEquals(ACCESS_PRIVATE, (int) $loaded->access_id);

        \_elgg_services()->session_manager->removeLoggedInUser();
        $code->delete();
    }

    /**
     * @return void
     */
    public function testEmbedFileOwnerCanEdit(): void {
        $owner = $this->createUser();
        $other = $this->createUser();

        \_elgg_services()->session_manager->setLoggedInUser($owner);

        $file = new File();
        $file->owner_guid = $owner->guid;
        $file->container_guid = $owner->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->save();

        \_elgg_services()->session_manager->removeLoggedInUser();

        $this->assertTrue($file->canEdit($owner->guid));
        $this->assertFalse($file->canEdit($other->guid));

        $file->delete();
    }

    /**
     * @return void
     */
    public function testEmbedFileCanBeDeleted(): void {
        $user = $this->createUser();
        \_elgg_services()->session_manager->setLoggedInUser($user);

        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->save();
        $guid = $file->guid;

        // Owner must be logged in to delete
        $this->assertTrue($file->delete());
        \_elgg_services()->session_manager->removeLoggedInUser();

        $this->assertEmpty(get_entity($guid));
    }
}

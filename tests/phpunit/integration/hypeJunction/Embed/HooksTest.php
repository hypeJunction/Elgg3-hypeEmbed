<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Hook handler tests: EmbedMenu, EntityEmbedMenu, Uploads icon hooks, Views lightbox filters.
 */
class HooksTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testEmbedMenuRegistersBasicItems(): void {
        $result = \elgg_trigger_plugin_hook('register', 'menu:embed', [
            'textarea_id' => 'test_ta',
        ], []);

        $this->assertIsArray($result);
        $names = array_map(fn($i) => $i->getName(), array_filter($result, fn($i) => $i instanceof \ElggMenuItem));
        $this->assertContains('posts', $names);
        $this->assertContains('player', $names);
    }

    /**
     * @return void
     */
    public function testEmbedMenuIncludesAdminItemsForAdmin(): void {
        $admin = $this->createUser();
        $admin->makeAdmin();
        \elgg_get_session()->setLoggedInUser($admin);

        $result = \elgg_trigger_plugin_hook('register', 'menu:embed', [
            'textarea_id' => 'test_ta',
        ], []);

        $names = array_map(
            fn($i) => $i->getName(),
            array_filter($result, fn($i) => $i instanceof \ElggMenuItem)
        );
        $this->assertContains('assets', $names);
        $this->assertContains('buttons', $names);
        $this->assertContains('code', $names);

        \elgg_get_session()->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testEmbedMenuExcludesAdminItemsForRegularUser(): void {
        $user = $this->createUser();
        \elgg_get_session()->setLoggedInUser($user);

        $result = \elgg_trigger_plugin_hook('register', 'menu:embed', [
            'textarea_id' => 'test_ta',
        ], []);

        $names = array_map(
            fn($i) => $i->getName(),
            array_filter($result, fn($i) => $i instanceof \ElggMenuItem)
        );
        $this->assertNotContains('assets', $names);
        $this->assertNotContains('buttons', $names);
        $this->assertNotContains('code', $names);

        \elgg_get_session()->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testEntityEmbedMenuReturnsCardItem(): void {
        $user = $this->createUser();
        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->save();

        $result = \elgg_trigger_plugin_hook('register', 'menu:embed:entity', [
            'entity' => $file,
        ], []);

        $this->assertIsArray($result);
        $names = array_map(
            fn($i) => $i->getName(),
            array_filter($result, fn($i) => $i instanceof \ElggMenuItem)
        );
        $this->assertContains('embed:card', $names);

        $file->delete();
    }

    /**
     * @return void
     */
    public function testUploadsSetIconSizesForEmbedFile(): void {
        $result = \elgg_trigger_plugin_hook('entity:icon:sizes', 'object', [
            'entity_subtype' => 'embed_file',
        ], []);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('small', $result);
        $this->assertArrayHasKey('medium', $result);
        $this->assertArrayHasKey('large', $result);
        $this->assertEquals(60, $result['small']['w']);
        $this->assertEquals(60, $result['small']['h']);
        $this->assertEquals(153, $result['medium']['w']);
        $this->assertEquals(600, $result['large']['w']);
    }

    /**
     * @return void
     */
    public function testUploadsSetIconSizesIgnoresOtherSubtypes(): void {
        $default = ['foo' => ['w' => 1, 'h' => 1]];
        $result = \elgg_trigger_plugin_hook('entity:icon:sizes', 'object', [
            'entity_subtype' => 'blog',
        ], $default);

        $this->assertEquals($default, $result);
    }

    /**
     * @return void
     */
    public function testLightboxLayoutFilterReturnsEmbedWhenParamSet(): void {
        set_input('embed_lightbox', '1');
        $result = Views::filterLightboxLayout();
        $this->assertEquals('embed_lightbox', $result);
        set_input('embed_lightbox', '');
    }

    /**
     * @return void
     */
    public function testLightboxLayoutFilterReturnsNullWhenParamAbsent(): void {
        set_input('embed_lightbox', '');
        $result = Views::filterLightboxLayout();
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testLightboxShellFilterReturnsEmbedWhenParamSet(): void {
        set_input('embed_lightbox', '1');
        $result = Views::filterLightboxShell();
        $this->assertEquals('embed_lightbox', $result);
        set_input('embed_lightbox', '');
    }
}

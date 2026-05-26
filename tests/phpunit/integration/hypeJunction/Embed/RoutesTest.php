<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Verify routes registered in elgg-plugin.php resolve to the correct paths.
 */
class RoutesTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testFileEmbedCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:file:embed');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testPostsEmbedCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:all:embed');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testEmbedTabRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('embed:tab');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testCkeditorImageRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('ckeditor:image');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testCkeditorAssetRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('ckeditor:asset');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testEmbedAssetRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('embed:asset');
        $this->assertNotNull($route);
    }

    /**
     * @return void
     */
    public function testEmbedTabRouteGeneratesExpectedUrl(): void {
        $url = \elgg_generate_url('embed:tab', ['tab' => 'player']);
        $this->assertStringContainsString('/embed/player', $url);
    }
}

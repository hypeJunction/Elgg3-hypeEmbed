<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Verify actions declared in elgg-plugin.php are registered with correct access.
 */
class ActionsRegistrationTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testFileUploadActionRegistered(): void {
        $this->assertTrue(elgg_action_exists('embed/file/upload'));
    }

    /**
     * @return void
     */
    public function testPlayerActionRegistered(): void {
        $this->assertTrue(elgg_action_exists('embed/player'));
    }

    /**
     * @return void
     */
    public function testButtonsActionRequiresAdmin(): void {
        $this->assertTrue(elgg_action_exists('embed/buttons'));
        $actions = _elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('embed/buttons', $actions);
        $this->assertEquals('admin', $actions['embed/buttons']['access']);
    }

    /**
     * @return void
     */
    public function testCodeActionRequiresAdmin(): void {
        $this->assertTrue(elgg_action_exists('embed/code'));
        $actions = _elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('embed/code', $actions);
        $this->assertEquals('admin', $actions['embed/code']['access']);
    }
}

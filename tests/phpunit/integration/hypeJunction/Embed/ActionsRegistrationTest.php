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
        $action = _elgg_services()->actions->getAction('embed/file/upload');
        $this->assertNotNull($action);
    }

    /**
     * @return void
     */
    public function testPlayerActionRegistered(): void {
        $action = _elgg_services()->actions->getAction('embed/player');
        $this->assertNotNull($action);
    }

    /**
     * @return void
     */
    public function testButtonsActionRequiresAdmin(): void {
        $action = _elgg_services()->actions->getAction('embed/buttons');
        $this->assertNotNull($action);
        $this->assertEquals('admin', $action->access);
    }

    /**
     * @return void
     */
    public function testCodeActionRequiresAdmin(): void {
        $action = _elgg_services()->actions->getAction('embed/code');
        $this->assertNotNull($action);
        $this->assertEquals('admin', $action->access);
    }
}

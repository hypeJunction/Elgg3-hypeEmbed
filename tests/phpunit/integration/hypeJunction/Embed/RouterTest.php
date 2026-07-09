<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Walled-garden public-page registration.
 *
 * Regression guard for commit 9d467c6: Router::setPublicPages was migrated to
 * the Elgg 7 \Elgg\Event signature (single Event arg, getValue() for the
 * incoming value, array return). Under the old 6.x hook signature the handler
 * would be handed only the Event and drop the incoming page list. This test
 * dispatches the handler through the real event system so a reverted signature
 * would surface as a lost value / non-array return.
 */
class RouterTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testSetPublicPagesAddsEmbedAndCkeditorPatterns(): void {
        $callback = [Router::class, 'setPublicPages'];

        elgg_register_event_handler('public_pages', 'walled_garden', $callback);

        try {
            $result = elgg_trigger_event_results('public_pages', 'walled_garden', [], [
                'existing/page',
            ]);
        } finally {
            elgg_unregister_event_handler('public_pages', 'walled_garden', $callback);
        }

        $this->assertIsArray($result);
        // Incoming value must be preserved (getValue passthrough)...
        $this->assertContains('existing/page', $result);
        // ...and both embed asset patterns appended.
        $this->assertContains('ckeditor/.*', $result);
        $this->assertContains('embed/.*', $result);
    }
}

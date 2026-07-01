<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * View rendering smoke tests — every key view must render without errors.
 */
class ViewsTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testEmbedSafeButtonViewRenders(): void {
        $output = elgg_view('embed/safe/button', [
            'text' => 'Click me',
            'url' => 'https://example.com',
            'type' => 'action',
            'target' => 'self',
        ]);
        // View may be empty when hypeShortcode is not active — just verify it does not throw
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testEmbedSafeCodeViewRenders(): void {
        $output = elgg_view('embed/safe/code', [
            'user_guid' => 1,
            'token' => 'abc123',
        ]);
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testEmbedSafePlayerViewRenders(): void {
        $output = elgg_view('embed/safe/player', [
            'url' => 'https://example.com/video.mp4',
        ]);
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testEmbedToolbarViewRenders(): void {
        $output = elgg_view('embed/toolbar', []);
        $this->assertIsString($output);
    }

    /**
     * Write-path / render regression: when the toolbar is rendered with a
     * textarea id, Elgg 7.x fataled on the old `elgg_format_element('', [], $id)`
     * (empty tag name is invalid in 7.x). This hit EVERY embed-enabled longtext
     * form but was invisible to GET render gating because the toolbar only
     * renders for a logged-in user on a non-embed context with an id set.
     * See bd elgg-migrate-ifpdo and commit 0ef817b.
     *
     * @return void
     */
    public function testEmbedToolbarEscapesTextareaId(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        elgg_push_context('default');

        try {
            $output = elgg_view('embed/toolbar', [
                'id' => 'ta"><script>',
                'embeds' => true,
            ]);
        } finally {
            elgg_pop_context();
            _elgg_services()->session_manager->removeLoggedInUser();
        }

        $this->assertIsString($output);
        $this->assertNotEmpty($output);
        // The id must be HTML-escaped into the attribute, not break out of it.
        $this->assertStringContainsString(
            'data-textarea-id="' . htmlspecialchars('ta"><script>', ENT_QUOTES, 'UTF-8') . '"',
            $output
        );
        $this->assertStringNotContainsString('data-textarea-id="ta"><script>', $output);
    }

    /**
     * @return void
     */
    public function testEmbedTabFileViewRenders(): void {
        $output = elgg_view('embed/tab/file', []);
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testEmbedTabPlayerViewRenders(): void {
        $output = elgg_view('embed/tab/player', []);
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testEmbedTabPostsViewRenders(): void {
        $output = elgg_view('embed/tab/posts', []);
        $this->assertIsString($output);
    }

    /**
     * @return void
     */
    public function testFormsEmbedButtonsExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/buttons'));
    }

    /**
     * @return void
     */
    public function testFormsEmbedCodeExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/code'));
    }

    /**
     * @return void
     */
    public function testFormsEmbedPlayerExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/player'));
    }

    /**
     * @return void
     */
    public function testShortcodeButtonViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/button'));
    }

    /**
     * @return void
     */
    public function testShortcodeCodeViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/code'));
    }

    /**
     * @return void
     */
    public function testShortcodeEmbedViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/embed'));
    }

    /**
     * @return void
     */
    public function testEmbedLightboxPageViewExists(): void {
        $this->assertTrue(elgg_view_exists('page/embed_lightbox'));
    }

    /**
     * @return void
     */
    public function testEmbedLightboxLayoutViewExists(): void {
        $this->assertTrue(elgg_view_exists('page/layouts/embed_lightbox'));
    }
}

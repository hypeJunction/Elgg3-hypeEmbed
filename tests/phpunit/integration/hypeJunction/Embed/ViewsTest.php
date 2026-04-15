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

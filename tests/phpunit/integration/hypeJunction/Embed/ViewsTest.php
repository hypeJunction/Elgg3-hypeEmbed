<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * View rendering smoke tests — every key view must render without errors.
 */
class ViewsTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function testEmbedSafeButtonViewRenders(): void {
        $output = elgg_view('embed/safe/button', [
            'text' => 'Click me',
            'url' => 'https://example.com',
            'type' => 'action',
            'target' => 'self',
        ]);
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
    }

    public function testEmbedSafeCodeViewRenders(): void {
        $output = elgg_view('embed/safe/code', [
            'user_guid' => 1,
            'token' => 'abc123',
        ]);
        $this->assertIsString($output);
    }

    public function testEmbedSafePlayerViewRenders(): void {
        $output = elgg_view('embed/safe/player', [
            'url' => 'https://example.com/video.mp4',
        ]);
        $this->assertIsString($output);
    }

    public function testEmbedToolbarViewRenders(): void {
        $output = elgg_view('embed/toolbar', []);
        $this->assertIsString($output);
    }

    public function testEmbedTabFileViewRenders(): void {
        $output = elgg_view('embed/tab/file', []);
        $this->assertIsString($output);
    }

    public function testEmbedTabPlayerViewRenders(): void {
        $output = elgg_view('embed/tab/player', []);
        $this->assertIsString($output);
    }

    public function testEmbedTabPostsViewRenders(): void {
        $output = elgg_view('embed/tab/posts', []);
        $this->assertIsString($output);
    }

    public function testFormsEmbedButtonsExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/buttons'));
    }

    public function testFormsEmbedCodeExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/code'));
    }

    public function testFormsEmbedPlayerExists(): void {
        $this->assertTrue(elgg_view_exists('forms/embed/player'));
    }

    public function testShortcodeButtonViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/button'));
    }

    public function testShortcodeCodeViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/code'));
    }

    public function testShortcodeEmbedViewExists(): void {
        $this->assertTrue(elgg_view_exists('shortcodes/embed'));
    }

    public function testEmbedLightboxPageViewExists(): void {
        $this->assertTrue(elgg_view_exists('page/embed_lightbox'));
    }

    public function testEmbedLightboxLayoutViewExists(): void {
        $this->assertTrue(elgg_view_exists('page/layouts/embed_lightbox'));
    }
}

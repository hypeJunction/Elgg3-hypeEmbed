<?php

namespace hypeJunction\Embed\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Static guard for the Elgg 7 ESM client migration (commits c4761d4 / 687f7a2).
 *
 * On Elgg 7 the importmap only registers `.mjs` modules; a client module left
 * as `.js` (AMD) silently never loads, so every embed toolbar/tab/list feature
 * dies without a fatal. The migration renamed all six embed modules to `.mjs`
 * and recreated the removed core `elgg/embed` shared-state module as
 * `elgg/embed.mjs`. This asserts those `.mjs` files exist and that no stale
 * `.js` twin was left behind to shadow/confuse the loader.
 */
final class ClientAssetsTest extends TestCase {

    /** @var string[] Modules that MUST exist as .mjs on the 7.x target. */
    private const REQUIRED_MJS = [
        'views/default/elgg/embed.mjs',
        'views/default/embed/toolbar.mjs',
        'views/default/embed/lists/item.mjs',
        'views/default/embed/tab/buttons.mjs',
        'views/default/embed/tab/code.mjs',
        'views/default/embed/tab/player.mjs',
        'views/default/embed/file_upload/content.mjs',
    ];

    private static function pluginRoot(): string {
        // tests/phpunit/integration/hypeJunction/Embed/ -> plugin root (5 up).
        return dirname(__DIR__, 5);
    }

    /**
     * @return void
     */
    public function testEmbedClientModulesAreEsm(): void {
        $root = self::pluginRoot();

        $missing = [];
        $stale = [];
        foreach (self::REQUIRED_MJS as $rel) {
            if (!is_file($root . '/' . $rel)) {
                $missing[] = $rel;
            }
            // A leftover .js twin would be loaded as AMD and shadow the .mjs.
            $jsTwin = substr($rel, 0, -4) . '.js';
            if (is_file($root . '/' . $jsTwin)) {
                $stale[] = $jsTwin;
            }
        }

        $this->assertSame([], $missing, "ESM modules missing on 7.x:\n" . implode("\n", $missing));
        $this->assertSame([], $stale, "Stale AMD .js twins shadow the .mjs loader:\n" . implode("\n", $stale));
    }
}

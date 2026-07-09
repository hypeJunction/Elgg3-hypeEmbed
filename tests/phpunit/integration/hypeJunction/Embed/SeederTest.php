<?php

namespace hypeJunction\Embed;

use Elgg\Database\Seeds\Seed;
use Elgg\IntegrationTestCase;

/**
 * Seeder shape + registration.
 *
 * Regression for commit cf5b6c2: the 6.1 Seed interface made getType() /
 * getCountOptions() required and added void return types. A Seeder missing the
 * concrete getType() fatals on every page load. This asserts the concrete
 * type is exposed and that the seeds,database handler appends the seeder class
 * (via the \Elgg\Event signature) without dropping the incoming list.
 */
class SeederTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * @return void
     */
    public function testSeederIsConcreteSeedSubclassForEmbedCode(): void {
        $this->assertTrue(is_subclass_of(Seeder::class, Seed::class));
        $this->assertEquals('embed_code', Seeder::getType());

        $count_options = (new Seeder([]))->getCountOptions();
        $this->assertEquals('object', $count_options['type']);
        $this->assertEquals('embed_code', $count_options['subtype']);
    }

    /**
     * @return void
     */
    public function testAddSeedAppendsSeederPreservingExisting(): void {
        $event = new \Elgg\Event(elgg(), 'seeds', 'database', ['Some\\Other\\Seed'], []);

        $seeds = Seeder::addSeed($event);

        $this->assertIsArray($seeds);
        $this->assertContains(Seeder::class, $seeds);
        $this->assertContains('Some\\Other\\Seed', $seeds);
    }
}

<?php

namespace hypeJunction\Embed;

use Elgg\IntegrationTestCase;

/**
 * Collection / search-field logic that was rewritten during the 6.x/7.x
 * migration.
 */
class CollectionsTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    /**
     * Regression for commit 98514f3: PostCollection::getSubtypes replaced the
     * removed get_registered_entity_types() with
     * elgg_entity_types_with_capability('searchable') and must still strip the
     * non-post subtypes (comment / file / discussion_reply) from the result —
     * even when they carry the searchable capability.
     *
     * @return void
     */
    public function testPostCollectionSubtypesExcludeNonPostSubtypes(): void {
        // A searchable subtype that must be excluded, and one that must survive.
        elgg_entity_enable_capability('object', 'file', 'searchable');
        elgg_entity_enable_capability('object', 'embedtest_keep', 'searchable');

        try {
            $subtypes = (new PostCollection())->getSubtypes();
        } finally {
            elgg_entity_disable_capability('object', 'file', 'searchable');
            elgg_entity_disable_capability('object', 'embedtest_keep', 'searchable');
        }

        $this->assertIsArray($subtypes);
        $this->assertNotContains('comment', $subtypes);
        $this->assertNotContains('file', $subtypes);
        $this->assertNotContains('discussion_reply', $subtypes);
        // A searchable, non-excluded subtype passes through.
        $this->assertContains('embedtest_keep', $subtypes);
    }

    /**
     * Regression for commit 73e91cb: FileTypeSearchField::getField queried the
     * metadata value through the wrong alias (a_table.value, the annotation
     * alias) which yields empty/erroring results for a metadata_names query. The
     * correct alias is n_table.value. With a real embed_file carrying a
     * `simpletype` value, the select must therefore be built AND expose that
     * value as an option — impossible if the alias were wrong.
     *
     * @return void
     */
    public function testFileTypeSearchFieldResolvesSimpletypeOption(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $file = new File();
        $file->owner_guid = $user->guid;
        $file->container_guid = $user->guid;
        $file->access_id = ACCESS_PUBLIC;
        $file->simpletype = 'image';
        $this->assertNotFalse($file->save());

        try {
            $field = new FileTypeSearchField(new PostCollection());
            $definition = $field->getField();

            $this->assertIsArray($definition);
            $this->assertEquals('select', $definition['#type']);
            // The grouped metadata value must appear as a selectable option.
            $this->assertArrayHasKey('image', $definition['options_values']);
        } finally {
            $file->delete();
            _elgg_services()->session_manager->removeLoggedInUser();
        }
    }
}

<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies image entity behavior — images_ui treats ElggFile objects with
 * simpletype=image as "images". These tests validate that assumption at
 * the data layer.
 *
 * Note: each test logs in as the entity owner before saving because Elgg 4.x
 * requires the logged-in user to have write access to the owner's container.
 */
class ImageEntityTest extends IntegrationTestCase {

    public function up() {}

    public function down() {
        \elgg_get_session()->removeLoggedInUser();
    }

    /**
     * @return string
     */
    public function getPluginID(): string {
        return 'images_ui';
    }

    /**
     * @return void
     */
    public function testFileEntityCanBeCreatedWithImageSimpletype(): void {
        $user = $this->createUser();
        \elgg_get_session()->setLoggedInUser($user);

        $entity = new \ElggFile();
        $entity->owner_guid = $user->guid;
        $entity->container_guid = $user->guid;
        $entity->access_id = ACCESS_PUBLIC;
        $entity->title = 'Test Image';
        $entity->simpletype = 'image';
        // Do not set mimetype to avoid triggering thumbnail creation on a non-existent file.
        $this->assertNotFalse($entity->save());

        \_elgg_services()->entityCache->delete($entity->guid);
        $loaded = get_entity($entity->guid);
        $this->assertInstanceOf(\ElggFile::class, $loaded);
        $this->assertEquals('image', $loaded->simpletype);

        $entity->delete();
    }

    /**
     * @return void
     */
    public function testOwnerCanEditImage(): void {
        $owner = $this->createUser();
        \elgg_get_session()->setLoggedInUser($owner);

        $entity = new \ElggFile();
        $entity->owner_guid = $owner->guid;
        $entity->container_guid = $owner->guid;
        $entity->access_id = ACCESS_PUBLIC;
        $entity->simpletype = 'image';
        $entity->save();

        $this->assertTrue($entity->canEdit($owner->guid));

        $entity->delete();
    }

    /**
     * @return void
     */
    public function testNonOwnerCannotEditImage(): void {
        $owner = $this->createUser();
        $other = $this->createUser();
        \elgg_get_session()->setLoggedInUser($owner);

        $entity = new \ElggFile();
        $entity->owner_guid = $owner->guid;
        $entity->container_guid = $owner->guid;
        $entity->access_id = ACCESS_PUBLIC;
        $entity->simpletype = 'image';
        $entity->save();

        $this->assertFalse($entity->canEdit($other->guid));

        $entity->delete();
    }

    /**
     * @return void
     */
    public function testImageTagsPersist(): void {
        $user = $this->createUser();
        \elgg_get_session()->setLoggedInUser($user);

        $entity = new \ElggFile();
        $entity->owner_guid = $user->guid;
        $entity->container_guid = $user->guid;
        $entity->access_id = ACCESS_PUBLIC;
        $entity->simpletype = 'image';
        $entity->tags = ['landscape', 'nature'];
        $entity->save();

        \_elgg_services()->entityCache->delete($entity->guid);
        $loaded = get_entity($entity->guid);
        $tags = (array) $loaded->tags;
        $this->assertContains('landscape', $tags);
        $this->assertContains('nature', $tags);

        $entity->delete();
    }

    /**
     * @return void
     */
    public function testCropCoordinatesPersistAsMetadata(): void {
        $user = $this->createUser();
        \elgg_get_session()->setLoggedInUser($user);

        $entity = new \ElggFile();
        $entity->owner_guid = $user->guid;
        $entity->container_guid = $user->guid;
        $entity->access_id = ACCESS_PUBLIC;
        $entity->simpletype = 'image';
        $entity->save();

        $entity->x1 = 10;
        $entity->y1 = 20;
        $entity->x2 = 100;
        $entity->y2 = 200;
        $entity->save();

        \_elgg_services()->entityCache->delete($entity->guid);
        $loaded = get_entity($entity->guid);
        $this->assertEquals(10, (int) $loaded->x1);
        $this->assertEquals(20, (int) $loaded->y1);
        $this->assertEquals(100, (int) $loaded->x2);
        $this->assertEquals(200, (int) $loaded->y2);

        $entity->delete();
    }
}

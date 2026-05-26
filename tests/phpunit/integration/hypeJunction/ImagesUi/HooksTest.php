<?php

namespace hypeJunction\ImagesUi;

use Elgg\Event;
use Elgg\IntegrationTestCase;

/**
 * Verifies that event handlers are registered in Bootstrap::init().
 */
class HooksTest extends IntegrationTestCase {

    public function up() {
        $libFile = dirname(__DIR__, 5) . '/lib/functions.php';
        if (file_exists($libFile) && !function_exists('images_ui_entity_url')) {
            require_once $libFile;
        }
    }

    public function down() {}

    public function getPluginID(): string {
        return 'images_ui';
    }

    public function testEntityUrlEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('entity:url', $events);
        $this->assertArrayHasKey('object', $events['entity:url']);
    }

    public function testMenuEntityEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('register', $events);
        $this->assertArrayHasKey('menu:entity', $events['register']);
    }

    public function testEntityIconUrlEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('entity:icon:url', $events);
        $this->assertArrayHasKey('object', $events['entity:icon:url']);
    }

    public function testUpdateEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('update:after', $events);
        $this->assertArrayHasKey('object', $events['update:after']);
    }

    public function testDeleteEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('delete', $events);
        $this->assertArrayHasKey('object', $events['delete']);
    }

    public function testHelperFunctionsExist(): void {
        $this->assertTrue(function_exists('images_ui_entity_url'));
        $this->assertTrue(function_exists('images_ui_setup_entity_menu'));
        $this->assertTrue(function_exists('images_ui_get_subtypes'));
    }

    public function testEntityUrlEventSkipsNonImage(): void {
        if (!function_exists('images')) {
            $this->markTestSkipped('images() helper not available; images plugin not loaded');
        }
        $user = $this->createUser();
        $object = $this->createObject(['subtype' => 'blog', 'owner_guid' => $user->guid]);
        $event = new Event(elgg(), 'entity:url', 'object', null, ['entity' => $object]);
        $result = \images_ui_entity_url($event);
        $this->assertNull($result);
    }

    public function testEntityMenuEventSkipsNonImage(): void {
        if (!function_exists('images')) {
            $this->markTestSkipped('images() helper not available');
        }
        $user = $this->createUser();
        $object = $this->createObject(['subtype' => 'blog', 'owner_guid' => $user->guid]);
        $event = new Event(elgg(), 'register', 'menu:entity', new \Elgg\Menu\MenuItems(), ['entity' => $object]);
        $result = \images_ui_setup_entity_menu($event);
        $this->assertNull($result);
    }
}

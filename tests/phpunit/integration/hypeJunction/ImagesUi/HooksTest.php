<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies that hook and event handlers are registered in Bootstrap::init().
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

    public function testEntityUrlHookRegistered(): void {
        $hooks = _elgg_services()->hooks->getAllHandlers();
        $this->assertArrayHasKey('entity:url', $hooks);
        $this->assertArrayHasKey('object', $hooks['entity:url']);
    }

    public function testMenuEntityHookRegistered(): void {
        $hooks = _elgg_services()->hooks->getAllHandlers();
        $this->assertArrayHasKey('register', $hooks);
        $this->assertArrayHasKey('menu:entity', $hooks['register']);
    }

    public function testEntityIconUrlHookRegistered(): void {
        $hooks = _elgg_services()->hooks->getAllHandlers();
        $this->assertArrayHasKey('entity:icon:url', $hooks);
        $this->assertArrayHasKey('object', $hooks['entity:icon:url']);
    }

    public function testUpdateEventRegistered(): void {
        $events = _elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('update:after', $events);
        $this->assertArrayHasKey('object', $events['update:after']);
    }

    public function testDeleteEventRegistered(): void {
        $events = _elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('delete', $events);
        $this->assertArrayHasKey('object', $events['delete']);
    }

    public function testHelperFunctionsExist(): void {
        $this->assertTrue(function_exists('images_ui_entity_url'));
        $this->assertTrue(function_exists('images_ui_setup_entity_menu'));
        $this->assertTrue(function_exists('images_ui_get_subtypes'));
        $this->assertTrue(function_exists('images_entity_icon_url'));
        $this->assertTrue(function_exists('images_update_event_handler'));
        $this->assertTrue(function_exists('images_delete_event_handler'));
    }

    public function testEntityUrlHookSkipsNonImage(): void {
        if (!function_exists('images')) {
            $this->markTestSkipped('images() helper not available; images plugin not loaded');
        }
        $user = $this->createUser();
        $object = $this->createObject(['subtype' => 'blog', 'owner_guid' => $user->guid]);
        $result = \images_ui_entity_url('entity:url', 'object', 'original', ['entity' => $object]);
        // Non-image returns void/null -> default URL unchanged
        $this->assertNull($result);
    }

    public function testEntityMenuHookSkipsNonImage(): void {
        if (!function_exists('images')) {
            $this->markTestSkipped('images() helper not available');
        }
        $user = $this->createUser();
        $object = $this->createObject(['subtype' => 'blog', 'owner_guid' => $user->guid]);
        $result = \images_ui_setup_entity_menu('register', 'menu:entity', [], ['entity' => $object]);
        $this->assertNull($result);
    }
}

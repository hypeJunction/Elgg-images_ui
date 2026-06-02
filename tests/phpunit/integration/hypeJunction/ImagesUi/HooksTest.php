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

    /**
     * @return string
     */
    public function getPluginID(): string {
        return 'images_ui';
    }

    /**
     * @return void
     */
    public function testEntityUrlHookRegistered(): void {
        $hooks = \_elgg_services()->hooks->getAllHandlers();
        $this->assertArrayHasKey('entity:url', $hooks);
        $this->assertArrayHasKey('object', $hooks['entity:url']);
    }

    /**
     * @return void
     */
    public function testMenuEntityHookRegistered(): void {
        $hooks = \_elgg_services()->hooks->getAllHandlers();
        $this->assertArrayHasKey('register', $hooks);
        $this->assertArrayHasKey('menu:entity', $hooks['register']);
    }

    /**
     * @return void
     */
    public function testEntityIconUrlHookRegistered(): void {
        // images dep registers entity:icon:url as an event (not a legacy hook) in its 4.x Bootstrap
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('entity:icon:url', $events);
        $this->assertArrayHasKey('object', $events['entity:icon:url']);
    }

    /**
     * @return void
     */
    public function testUpdateEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('update:after', $events);
        $this->assertArrayHasKey('object', $events['update:after']);
    }

    /**
     * @return void
     */
    public function testDeleteEventRegistered(): void {
        $events = \_elgg_services()->events->getAllHandlers();
        $this->assertArrayHasKey('delete', $events);
        $this->assertArrayHasKey('object', $events['delete']);
    }

    /**
     * @return void
     */
    public function testHelperFunctionsExist(): void {
        $this->assertTrue(function_exists('images_ui_entity_url'));
        $this->assertTrue(function_exists('images_ui_setup_entity_menu'));
        $this->assertTrue(function_exists('images_ui_get_subtypes'));
        // images_entity_icon_url, images_update_event_handler, images_delete_event_handler
        // are now anonymous closures in hypeJunction\Images\Bootstrap — no global function names.
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

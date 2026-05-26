<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies that plugin actions are registered.
 */
class ActionsTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function getPluginID(): string {
        return 'images_ui';
    }

    public function testUploadActionRegistered(): void {
        $actions = \_elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('images/upload', $actions);
    }

    public function testCropActionRegistered(): void {
        $actions = \_elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('images/crop', $actions);
    }

    public function testThumbsActionRegistered(): void {
        $actions = \_elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('images/thumbs', $actions);
    }
}

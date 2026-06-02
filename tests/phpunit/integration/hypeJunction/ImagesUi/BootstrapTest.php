<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies the Bootstrap class conforms to Elgg 4.x PluginBootstrap contract.
 */
class BootstrapTest extends IntegrationTestCase {

    public function up() {}

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
    public function testBootstrapClassExists(): void {
        $this->assertTrue(class_exists(Bootstrap::class));
    }

    /**
     * @return void
     */
    public function testBootstrapExtendsPluginBootstrap(): void {
        $this->assertTrue(is_subclass_of(Bootstrap::class, \Elgg\PluginBootstrap::class));
    }

    /**
     * @return void
     */
    public function testBootstrapImplementsRequiredMethods(): void {
        $required = ['load', 'boot', 'init', 'ready', 'shutdown', 'activate', 'deactivate', 'upgrade'];
        foreach ($required as $method) {
            $this->assertTrue(
                method_exists(Bootstrap::class, $method),
                "Bootstrap::{$method}() must exist for Elgg 4.x"
            );
        }
    }

    /**
     * @return void
     */
    public function testSiteMenuItemRegistered(): void {
        $menu = \_elgg_services()->menus->getMenu('site', []);
        $found = false;
        foreach ($menu->getSections() as $section) {
            foreach ($section->getItems() as $item) {
                if ($item->getName() === 'images') {
                    $found = true;
                    break 2;
                }
            }
        }
        $this->assertTrue($found, 'Expected "images" item in site menu');
    }
}

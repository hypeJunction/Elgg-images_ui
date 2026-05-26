<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies that routes registered in elgg-plugin.php resolve.
 */
class RoutesTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function getPluginID(): string {
        return 'images_ui';
    }

    public function testCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:image');
        $this->assertNotNull($route);
        $this->assertEquals('/images/all', $route->getPath());
    }

    public function testOwnerCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:image:owner');
        $this->assertNotNull($route);
        $this->assertStringContainsString('/images/owner/', $route->getPath());
    }

    public function testFriendsCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:image:friends');
        $this->assertNotNull($route);
    }

    public function testGroupsCollectionRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('collection:object:image:groups');
        $this->assertNotNull($route);
    }

    public function testAddRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('add:object:image');
        $this->assertNotNull($route);
        $this->assertStringContainsString('/images/upload', $route->getPath());
    }

    public function testEditRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('edit:object:image');
        $this->assertNotNull($route);
    }

    public function testViewRouteRegistered(): void {
        $route = \_elgg_services()->routes->get('view:object:image');
        $this->assertNotNull($route);
    }
}

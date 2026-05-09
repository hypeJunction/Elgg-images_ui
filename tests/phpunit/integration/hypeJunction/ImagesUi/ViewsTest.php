<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies that plugin views exist.
 */
class ViewsTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function getPluginID(): string {
        return 'images_ui';
    }

    public function testUploadFormViewExists(): void {
        $this->assertTrue(elgg_view_exists('forms/images/upload'));
    }

    public function testCropFormViewExists(): void {
        $this->assertTrue(elgg_view_exists('forms/images/crop'));
    }

    public function testThumbsFormViewExists(): void {
        $this->assertTrue(elgg_view_exists('forms/images/thumbs'));
    }

    public function testAllResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/all'));
    }

    public function testViewResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/view'));
    }

    public function testEditResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/edit'));
    }

    public function testUploadResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/upload'));
    }

    public function testFriendsResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/friends'));
    }

    public function testGroupsResourceViewExists(): void {
        $this->assertTrue(elgg_view_exists('resources/images/groups'));
    }

    public function testImagesListItemViewExists(): void {
        $this->assertTrue(elgg_view_exists('lists/images/item'));
    }

    public function testAllFilterViewExists(): void {
        $this->assertTrue(elgg_view_exists('filters/images/all'));
    }

    public function testRiverViewExists(): void {
        $this->assertTrue(elgg_view_exists('river/object/image'));
    }

    public function testProfileViewExists(): void {
        $this->assertTrue(elgg_view_exists('profile/object/image'));
    }
}

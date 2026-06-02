<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Verifies that plugin views exist.
 */
class ViewsTest extends IntegrationTestCase {

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
    public function testUploadFormViewExists(): void {
        $this->assertTrue(\elgg_view_exists('forms/images/upload'));
    }

    /**
     * @return void
     */
    public function testCropFormViewExists(): void {
        $this->assertTrue(\elgg_view_exists('forms/images/crop'));
    }

    /**
     * @return void
     */
    public function testThumbsFormViewExists(): void {
        $this->assertTrue(\elgg_view_exists('forms/images/thumbs'));
    }

    /**
     * @return void
     */
    public function testAllResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/all'));
    }

    /**
     * @return void
     */
    public function testViewResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/view'));
    }

    /**
     * @return void
     */
    public function testEditResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/edit'));
    }

    /**
     * @return void
     */
    public function testUploadResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/upload'));
    }

    /**
     * @return void
     */
    public function testFriendsResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/friends'));
    }

    /**
     * @return void
     */
    public function testGroupsResourceViewExists(): void {
        $this->assertTrue(\elgg_view_exists('resources/images/groups'));
    }

    /**
     * @return void
     */
    public function testImagesListItemViewExists(): void {
        $this->assertTrue(\elgg_view_exists('lists/images/item'));
    }

    /**
     * @return void
     */
    public function testAllFilterViewExists(): void {
        $this->assertTrue(\elgg_view_exists('filters/images/all'));
    }

    /**
     * @return void
     */
    public function testRiverViewExists(): void {
        $this->assertTrue(\elgg_view_exists('river/object/image'));
    }

    /**
     * @return void
     */
    public function testProfileViewExists(): void {
        $this->assertTrue(\elgg_view_exists('profile/object/image'));
    }
}

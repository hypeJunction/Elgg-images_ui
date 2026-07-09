<?php

namespace hypeJunction\ImagesUi;

use Elgg\IntegrationTestCase;

/**
 * Regression guards for the Elgg 6.x -> 7.x migration fixes applied to
 * images_ui. Each test pins one concrete fix so a future forward-port that
 * resurrects the removed API fails loudly. Refs are the migration commits.
 *
 * Most assertions are source-level: the broken forms (getTablePrefix(),
 * get_user_by_username(), elgg_load_js('lightbox'), detectMimeType(), an
 * un-cast get_entity() guid, elgg_entity_gatekeeper() with no arg) fatal only
 * at page-render on 7.x, so catching the signature in the source is the
 * deterministic gate. testFriendsFilteredListRendersWithoutFatal exercises the
 * dbprefix fix at runtime against a booted DB.
 */
class MigrationFixesTest extends IntegrationTestCase {

    public function up() {
        $lib = $this->pluginRoot() . '/lib/functions.php';
        if (file_exists($lib) && !function_exists('images_ui_get_subtypes')) {
            require_once $lib;
        }
    }

    public function down() {
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    public function getPluginID(): string {
        return 'images_ui';
    }

    private function pluginRoot(): string {
        return dirname(__DIR__, 5);
    }

    private function read(string $relative): string {
        $path = $this->pluginRoot() . '/' . ltrim($relative, '/');
        $this->assertFileExists($path);
        return (string) file_get_contents($path);
    }

    /**
     * f7896b5 — friends/groups list where-clauses must build the
     * entity_relationships subquery from elgg_get_config('dbprefix'), not the
     * removed Database::getTablePrefix().
     */
    public function testAllListUsesConfigDbprefixNotGetTablePrefix(): void {
        $src = $this->read('views/default/lists/images/all.php');
        $this->assertStringContainsString("elgg_get_config('dbprefix')", $src);
        $this->assertStringNotContainsString('getTablePrefix', $src,
            'getTablePrefix() was removed — the friends/groups subquery must use elgg_get_config(\'dbprefix\')');
        $this->assertStringContainsString('entity_relationships', $src);
    }

    /**
     * 3db9f31 — get_user_by_username() was removed in 5.x. The friends and
     * groups resources must resolve the page owner via elgg_get_user_by_username()
     * and throw EntityPermissionsException for an unknown username.
     */
    public function testFriendsAndGroupsResourcesResolveUserViaElggHelper(): void {
        foreach (['friends', 'groups'] as $collection) {
            $src = $this->read("views/default/resources/images/{$collection}.php");
            $this->assertStringContainsString('elgg_get_user_by_username(', $src,
                "resources/images/{$collection} must use elgg_get_user_by_username()");
            $this->assertDoesNotMatchRegularExpression('/(?<![\w>])get_user_by_username\s*\(/', $src,
                "resources/images/{$collection} still calls the removed get_user_by_username()");
            $this->assertStringContainsString('EntityPermissionsException', $src,
                "resources/images/{$collection} must reject an unknown username");
        }
    }

    /**
     * 80c4bcb — lightbox asset loading moved from AMD (elgg_load_js/elgg_load_css)
     * to ESM: elgg_import_esm('elgg/lightbox') + elgg_load_external_file('css',...).
     */
    public function testLightboxLoadedViaEsmNotAmd(): void {
        $src = $this->read('views/default/file/specialcontent/image/default.php');
        $this->assertStringContainsString("elgg_import_esm('elgg/lightbox')", $src);
        $this->assertStringContainsString("elgg_load_external_file('css', 'lightbox')", $src);
        $this->assertStringNotContainsString('elgg_load_js', $src,
            'elgg_load_js() was removed in 6.x — lightbox JS must load via elgg_import_esm()');
        $this->assertStringNotContainsString('elgg_load_css', $src,
            'elgg_load_css() was removed in 4.x — lightbox CSS must load via elgg_load_external_file()');
    }

    /**
     * f4b3cc5 — ElggFile::detectMimeType() was removed in 4.x; the image profile
     * view must fall back to getMimeType().
     */
    public function testProfileViewUsesGetMimeTypeNotDetect(): void {
        $src = $this->read('views/default/profile/object/image.php');
        $this->assertStringContainsString('->getMimeType()', $src);
        $this->assertStringNotContainsString('detectMimeType', $src,
            'detectMimeType() was removed in 4.x — use getMimeType()');
    }

    /**
     * f4b3cc5 / 6c754c6 — get_entity() needs an int guid on 5.x+; the three
     * actions must resolve the target as $params->guid ? get_entity((int) ...) : null.
     */
    public function testActionsGuardEntityLookupWithIntCast(): void {
        foreach (['upload', 'crop', 'thumbs'] as $action) {
            $src = $this->read("actions/images/{$action}.php");
            $this->assertMatchesRegularExpression('/get_entity\(\(int\)\s*\$params->guid\)/', $src,
                "actions/images/{$action} must cast the guid to int before get_entity()");
            $this->assertStringContainsString(': null', $src,
                "actions/images/{$action} must null-guard a missing guid");
        }
    }

    /**
     * f4b3cc5 — elgg_entity_gatekeeper() now requires a guid argument. Every
     * resource that gatekeeps must pass a concrete ->guid, never call it bare.
     */
    public function testResourceGatekeepersReceiveGuidArgument(): void {
        foreach (['all', 'view', 'upload'] as $resource) {
            $src = $this->read("views/default/resources/images/{$resource}.php");
            $this->assertMatchesRegularExpression('/elgg_entity_gatekeeper\(\s*\$\w+->[\w]*guid\s*\)/', $src,
                "resources/images/{$resource} must call elgg_entity_gatekeeper() with a guid");
            $this->assertDoesNotMatchRegularExpression('/elgg_entity_gatekeeper\(\s*\)/', $src,
                "resources/images/{$resource} calls elgg_entity_gatekeeper() with no guid (removed 5.x form)");
        }
    }

    /**
     * d8aa824 — the composer.json 'version' field shadows the git tag at install
     * time and must be dropped.
     */
    public function testComposerHasNoVersionField(): void {
        $composer = json_decode($this->read('composer.json'), true);
        $this->assertIsArray($composer);
        $this->assertArrayNotHasKey('version', $composer,
            'composer.json "version" field shadows the git tag — drop it');
    }

    /**
     * f7896b5 (runtime) — rendering the friends-filtered list must execute the
     * entity_relationships subquery built from elgg_get_config('dbprefix')
     * without fatalling. The removed getTablePrefix() form would throw here.
     */
    public function testFriendsFilteredListRendersWithoutFatal(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);
        elgg_set_page_owner_guid($user->guid);

        $out = elgg_view('lists/images/all', ['filter' => 'friends']);
        $this->assertIsString($out);

        $out_groups = elgg_view('lists/images/all', ['filter' => 'groups']);
        $this->assertIsString($out_groups);
    }
}

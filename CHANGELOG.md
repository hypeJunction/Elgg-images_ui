<a name="6.0.0"></a>
## 6.0.0 (2026-05-09)

### Breaking Changes

* **elgg:** raise minimum to Elgg 6.x (PHP 8.1+).

### Migration (5.x → 6.x)

* **composer:** `elgg/elgg ~6.1.0`, PHP `>=8.1`, added `ext-intl`.
* **docker:** test stack added for Elgg 6.x (docker/elgg6/).
* No data migration required.

<a name="5.0.0"></a>
## 5.0.0 (2026-05-05)

### Breaking Changes

* Requires Elgg 5.x (`elgg/elgg ^5.0`, PHP >=8.2)
* Plugin hooks (`elgg_register_plugin_hook_handler`) fully replaced by the unified events API (`elgg_register_event_handler`)
* All hook handler callbacks now use `\Elgg\Event` signature (4-arg callbacks removed)

### Features

* Route middleware: `UserPageOwnerGatekeeper` added to owner, friends, and groups collection routes
* Docker test stack upgraded to PHP 8.2-apache, MySQL 8.0, Elgg 5.x, Playwright 1.59.1

### Bug Fixes

* `get_default_access()` removed in Elgg 5.x — replaced with `elgg_get_config('default_access')` in upload action
* `current_page_url()` removed in Elgg 5.x — replaced with `elgg_get_current_url()` in filter and list views
* Playwright config: `baseURL` moved inside `use:{}` block (required for Playwright >=1.50)

---

<a name="4.0.0"></a>
## 4.0.0 (2026-04-16)

### Breaking Changes

* Requires Elgg 4.x (`elgg/elgg ^4.0`, PHP >=7.4)
* Removed `start.php` and `manifest.xml` — `elgg-plugin.php` is now the sole plugin descriptor

### Features

* Bootstrap class (`hypeJunction\ImagesUi\Bootstrap`) replaces `start.php`
* Routes and actions declared declaratively in `elgg-plugin.php`
* `images` is now a required Composer dependency (standalone service plugin)

### Bug Fixes

* `dbprefix` subquery access uses `elgg_get_config('dbprefix')` (`Database::getTablePrefix()` was removed in Elgg 3.0)
* `ElggFile::detectMimeType()` removed in Elgg 4.x — `ImageService::isImage()` updated to use `mime_content_type()` when file exists on disk

<a name="1.0.2"></a>
## [1.0.2](https://github.com/hypeJunction/Elgg-images_ui/compare/1.0.1...v1.0.2) (2016-01-06)


### Bug Fixes

* **actions:** do not delete entity ([0a9f7b1](https://github.com/hypeJunction/Elgg-images_ui/commit/0a9f7b1))
* **filters:** fix default filter context ([98bb995](https://github.com/hypeJunction/Elgg-images_ui/commit/98bb995))

### Features

* **filters:** make entity available to the edit page filter ([80e273c](https://github.com/hypeJunction/Elgg-images_ui/commit/80e273c))



<a name="1.0.1"></a>
## [1.0.1](https://github.com/hypeJunction/Elgg-images_ui/compare/1.0.0...v1.0.1) (2015-12-29)


### Bug Fixes

* **lists:** fix typo in subtype variable name ([6d212eb](https://github.com/hypeJunction/Elgg-images_ui/commit/6d212eb))



<a name="1.0.0"></a>
# 1.0.0 (2015-12-29)


### Features

* **releases:** initial commit ([3b4c272](https://github.com/hypeJunction/Elgg-images_ui/commit/3b4c272))




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




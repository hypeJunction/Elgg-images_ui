# images_ui — Architecture (Elgg 4.x)

## Plugin Summary

images_ui provides the UI layer for image management on an Elgg 4.x site. It
depends on the `images` plugin (standalone service layer) which handles image
storage, thumbnail generation, and MIME detection. images_ui registers routes,
actions, views, menus, and hook handlers to expose image upload, crop, and
browsing functionality.

## Directory Structure

```
images_ui/
├── actions/images/        # upload.php, crop.php, thumbs.php
├── classes/hypeJunction/ImagesUi/
│   └── Bootstrap.php      # PluginBootstrap — init hooks, routes, menus
├── docker/                # Per-plugin Elgg 4.x Docker test stack
├── languages/             # i18n strings
├── lib/
│   └── functions.php      # Global hook callback functions
├── tests/                 # PHPUnit + Playwright test suites
├── views/default/
│   ├── filters/images/    # Filter tabs (all, edit)
│   ├── forms/images/      # upload, crop, thumbs forms
│   ├── input/images/      # container selector widget
│   ├── lists/images/      # item.php, all.php (entity list)
│   ├── profile/object/    # image profile view
│   ├── resources/images/  # page controllers (all, view, edit, upload, etc.)
│   └── river/object/      # river view for image activity
├── composer.json
└── elgg-plugin.php
```

## Registered Hooks (Elgg 4.x plugin hooks)

| Hook | Type | Handler | Purpose |
|------|------|---------|---------|
| `entity:url` | `object` | `images_ui_entity_url` | Returns `/images/view/{guid}` URL for image entities |
| `register` | `menu:entity` | `images_ui_setup_entity_menu` | Adds edit/delete menu items for image owners |

## Registered Events (Elgg 4.x events)

None registered directly by images_ui — entity lifecycle events (create, update, delete) are registered by the `images` dep plugin.

## Routes

| Route name | Path | Resource view |
|-----------|------|--------------|
| `collection:object:image` | `/images/all` | `resources/images/all` |
| `collection:object:image:owner` | `/images/owner/{username}` | `resources/images/all` |
| `collection:object:image:friends` | `/images/friends/{username}` | `resources/images/friends` |
| `collection:object:image:groups` | `/images/groups/{username}` | `resources/images/groups` |
| `add:object:image` | `/images/upload/{container_guid?}` | `resources/images/upload` |
| `edit:object:image` | `/images/edit/{guid}` | `resources/images/edit` |
| `view:object:image` | `/images/view/{guid}` | `resources/images/view` |

## Actions

| Action | Access | Description |
|--------|--------|-------------|
| `images/upload` | (default) | Upload an image file |
| `images/crop` | (default) | Crop a saved image |
| `images/thumbs` | (default) | Regenerate thumbnails |

## Dependencies

| Plugin | Position | Required |
|--------|----------|---------|
| `images` | after | Yes — provides ImageService, thumbnail generation, and MIME detection |

## Entity Model

images_ui does not register its own entity types. Images are `ElggFile` objects
with `simpletype = 'image'`. The `images` plugin's ImageService checks both
`mimetype` and `simpletype` to identify image entities.

Key metadata properties:

| Property | Purpose |
|----------|---------|
| `simpletype` | `'image'` — marks the file as an image |
| `mimetype` | MIME type (e.g. `image/jpeg`) — used by ImageService::isImage() |
| `tags` | User-assigned tags stored as Elgg metadata |
| `x1`, `y1`, `x2`, `y2` | Crop coordinates stored as metadata |
| `icontime` | Timestamp of last thumbnail generation |

## CSS

images_ui extends `css/elgg` with the `images_ui.css` view, injected via
`elgg_extend_view()` in Bootstrap::init().

## Migration Notes (3.x → 4.x)

- Removed `start.php` (Elgg 4.x rejects plugins with start.php)
- Removed `manifest.xml` (composer.json is the sole metadata source in 4.x)
- Updated composer.json: `php >=7.4`, `elgg/elgg ^4.0`, `composer/installers ^2.0`
- Added `hypejunction/images` as a required Composer dep
- `elgg_register_css()` / `elgg_load_css()` removed in 4.x → replaced with `elgg_extend_view('css/elgg', 'images_ui.css')`
- `dbprefix` subquery access in `lists/images/all.php` updated to use `elgg()->db->getTablePrefix()`
- Bug fix in `images` dep plugin: `ElggFile::detectMimeType()` was removed in Elgg 4.x; `ImageService::isImage()` updated to fall back to `mime_content_type()` only when the file exists on disk
- Hook callback signatures: guarded for both 4.x 1-arg `\Elgg\Hook` and legacy 4-arg style

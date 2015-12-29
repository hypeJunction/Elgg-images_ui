Image UI for Elgg
=================
![Elgg 1.11](https://img.shields.io/badge/Elgg-1.11.x-orange.svg?style=flat-square)
![Elgg 1.12](https://img.shields.io/badge/Elgg-1.12.x-orange.svg?style=flat-square)
![Elgg 2.0](https://img.shields.io/badge/Elgg-2.0.x-orange.svg?style=flat-square)

## Features

 * Standadized UI for listing, displaying and cropping image files

![Feed](https://raw.github.com/hypeJunction/Elgg-images_ui/master/screenshots/feed.png "Image feed")
![Edit](https://raw.github.com/hypeJunction/Elgg-images_ui/master/screenshots/edit.png "Image editing interface")

## Hooks

 * `'get_subtypes','images'` - filters object subtypes that may contain image files
 * `'list_options', 'lists/images/all'` - filters options passed to `elgg_list_entities()`
 * `'containers', 'images'` - filters available containers (e.g. user's albums) to be displayed as an option in the upload form
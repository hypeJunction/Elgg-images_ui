<?php

namespace hypeJunction\ImagesUi;

use Elgg\DefaultPluginBootstrap;
use Elgg\Includer;

/**
 * Plugin bootstrap for images_ui.
 */
class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		Includer::requireFileOnce($this->plugin->getPath() . '/lib/functions.php');

		elgg_register_menu_item('site', [
			'name' => 'images',
			'text' => elgg_echo('images'),
			'href' => '/images/all',
		]);

		elgg_register_event_handler('entity:url', 'object', 'images_ui_entity_url');
		elgg_register_event_handler('register', 'menu:entity', 'images_ui_setup_entity_menu');
	}
}

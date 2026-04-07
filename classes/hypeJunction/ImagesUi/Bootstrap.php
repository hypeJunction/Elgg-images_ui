<?php

namespace hypeJunction\ImagesUi;

use Elgg\Includer;
use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function load() {
		Includer::requireFileOnce($this->plugin->getPath() . '/autoloader.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {

	}

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

		elgg_register_plugin_hook_handler('entity:url', 'object', 'images_ui_entity_url');
		elgg_register_plugin_hook_handler('register', 'menu:entity', 'images_ui_setup_entity_menu');

		elgg_extend_view('css/elgg', 'images_ui.css');

		// Image submodule hooks
		elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'images_entity_icon_url');
		elgg_register_event_handler('update:after', 'object', 'images_update_event_handler');
		elgg_register_event_handler('delete', 'object', 'images_delete_event_handler');
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}
}

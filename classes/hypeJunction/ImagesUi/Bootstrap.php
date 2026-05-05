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

		elgg_register_event_handler('entity:url', 'object', 'images_ui_entity_url');
		elgg_register_event_handler('register', 'menu:entity', 'images_ui_setup_entity_menu');

		elgg_extend_view('css/elgg', 'images_ui.css');
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

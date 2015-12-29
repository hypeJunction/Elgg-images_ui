<?php

/**
 * Images UI
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'images_ui_init');

/**
 * Initialize the plugin
 * @return void
 */
function images_ui_init() {

	elgg_register_menu_item('site', [
		'name' => 'images',
		'text' => elgg_echo('images'),
		'href' => '/images/all',
	]);

	elgg_register_plugin_hook_handler('entity:url', 'object', 'images_ui_entity_url');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'images_ui_setup_entity_menu');

	elgg_register_action('images/upload', __DIR__ . '/actions/images/upload.php');
	elgg_register_action('images/crop', __DIR__ . '/actions/images/crop.php');
	elgg_register_action('images/thumbs', __DIR__ . '/actions/images/thumbs.php');

	elgg_register_page_handler('images', 'images_ui_page_handler');

	elgg_extend_view('css/elgg', 'images_ui.css');
}

/**
 * Get object subtypes that may contain image files
 * Image files must have 'simpletype' metadata set to 'image'
 *
 * @param array $params Params to pass to the hook
 */
function images_ui_get_subtypes(array $params = []) {
	return elgg_trigger_plugin_hook('get_subtypes', 'images', $params, ['file']);
}

/**
 * Image URL handler
 *
 * @param string $hook   "entity:url"
 * @param string $type   "object"
 * @param string $return URL
 * @param array  $params Hook params
 * @return array
 */
function images_ui_entity_url($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!images()->isImage($entity)) {
		return;
	}

	return elgg_normalize_url("/images/view/$entity->guid");
}

/**
 * Setup image menu
 * 
 * @param string         $hook   "register"
 * @param string         $type   "menu:entity"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function images_ui_setup_entity_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (!images()->isImage($entity)) {
		return;
	}

	if ($entity->canEdit()) {
		$return[] = ElggMenuItem::factory([
					'name' => 'edit',
					'text' => elgg_echo('edit'),
					'title' => elgg_echo('edit:this'),
					'href' => "/images/edit/{$entity->guid}",
					'priority' => 200,
		]);
		$return[] = ElggMenuItem::factory([
					'name' => 'delete',
					'text' => elgg_view_icon('delete'),
					'title' => elgg_echo('delete:this'),
					'href' => "/action/entity/delete?guid={$entity->guid}",
					'confirm' => elgg_echo('deleteconfirm'),
					'priority' => 300,
		]);
	}

	return $return;
}

/**
 * Page handler
 *
 * @param array  $segments   URL segments
 * @param string $identifier Page Identifier
 * @return bool
 */
function images_ui_page_handler($segments, $identifier) {

	$page = array_shift($segments);

	switch ($page) {
		default :
		case 'all' :
		case 'list' :
		case 'group' :
			echo elgg_view("resources/images/all", [
				'target_guid' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;

		case 'owner' :
			$username = $segments[0];
			$user = get_user_by_username($username);
			if (!$user) {
				return false;
			}
			echo elgg_view("resources/images/all", [
				'target_guid' => $user->guid,
				'identifier' => $identifier,
			]);
			return true;

		case 'friends' :
			echo elgg_view("resources/images/friends", [
				'username' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;

		case 'groups' :
			echo elgg_view("resources/images/groups", [
				'username' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;

		case 'add' :
		case 'upload' :
			echo elgg_view("resources/images/upload", [
				'container_guid' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;

		case 'edit' :
			echo elgg_view("resources/images/edit", [
				'guid' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;

		case 'view' :
			echo elgg_view("resources/images/view", [
				'guid' => $segments[0],
				'identifier' => $identifier,
			]);
			return true;
	}

	return false;
}

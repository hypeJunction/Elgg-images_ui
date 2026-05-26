<?php

/**
 * Get object subtypes that may contain image files
 * Image files must have 'simpletype' metadata set to 'image'
 *
 * @param array $params Params to pass to the hook
 * @return array
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
 * @return string|void
 */
function images_ui_entity_url(\Elgg\Hook $hook) {
	if ($hook instanceof \Elgg\Hook) {
		$hook->getParams() = $hook->getParams();
	}
	$entity = elgg_extract('entity', (array) $hook->getParams());
	if (!function_exists('images') || !images()->isImage($entity)) {
		return;
	}
	return elgg_normalize_url("/images/view/{$entity->guid}");
}

/**
 * Setup image menu
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:entity"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]|void
 */
function images_ui_setup_entity_menu(\Elgg\Hook $hook) {
		$return = $hook->getValue();

	if ($hook instanceof \Elgg\Hook) {
		$hook->getParams() = $hook->getParams();
		$return = $hook->getValue();
	}
	$entity = elgg_extract('entity', (array) $hook->getParams());
	if (!function_exists('images') || !images()->isImage($entity)) {
		return;
	}
	$return = (array) $return;
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


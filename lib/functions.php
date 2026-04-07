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
function images_ui_entity_url($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if (!images()->isImage($entity)) {
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
 * Icon URL handler for image entities
 */
function images_entity_icon_url($hook, $type, $return, $params) {
	$size = elgg_extract('size', $params, 'medium');
	$entity = elgg_extract('entity', $params);
	if (!images()->isImage($entity)) {
		return;
	}
	$thumb = images()->getThumb($entity, $size);
	if (!$thumb) {
		return;
	}
	return elgg_get_inline_url($thumb, true);
}

/**
 * Update event handler for image entities
 */
function images_update_event_handler($event, $type, $entity) {
	if (!images()->isImage($entity)) {
		return;
	}
	if ($entity->icon_owner_guid && $entity->icon_owner_guid != $entity->owner_guid) {
		images()->clearThumbs($entity);
	}
	$mtime = filemtime($entity->getFilenameOnFilestore());
	if (!$entity->icontime || $entity->icontime != $mtime) {
		if (images()->createThumbs($entity)) {
			$entity->icontime = $mtime;
		} else {
			return false;
		}
	}
}

/**
 * Delete event handler for image entities
 */
function images_delete_event_handler($event, $type, $entity) {
	images()->clearThumbs($entity);
}

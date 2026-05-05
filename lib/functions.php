<?php

/**
 * Get object subtypes that may contain image files
 * Image files must have 'simpletype' metadata set to 'image'
 *
 * @param array $params Params to pass to the event
 * @return array
 */
function images_ui_get_subtypes(array $params = []) {
	return elgg_trigger_event_results('get_subtypes', 'images', $params, ['file']);
}

/**
 * Image URL handler
 *
 * @param \Elgg\Event $event "entity:url" "object"
 * @return string|void
 */
function images_ui_entity_url(\Elgg\Event $event) {
	$entity = $event->getEntityParam();
	if (!function_exists('images') || !images()->isImage($entity)) {
		return;
	}
	return elgg_normalize_url("/images/view/{$entity->guid}");
}

/**
 * Setup image menu
 *
 * @param \Elgg\Event $event "register" "menu:entity"
 * @return \Elgg\Collections\Collection|void
 */
function images_ui_setup_entity_menu(\Elgg\Event $event) {
	$entity = $event->getEntityParam();
	if (!function_exists('images') || !images()->isImage($entity)) {
		return;
	}
	$return = $event->getValue();
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

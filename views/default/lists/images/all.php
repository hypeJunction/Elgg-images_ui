<?php

$subtypes = elgg_extract('subtypes', $vars, images_ui_get_subtypes($vars));
$owner_guids = elgg_extract('owner_guids', $vars);
$container_guids = elgg_extract('container_guids', $vars);
$list_type = elgg_extract('list_type', $vars, get_input('list_type', 'list'));
$filter = elgg_extract('filter', $vars);

$options = [
	'types' => 'object',
	'subtypes' => $subtype,
	'owner_guids' => $owner_guids ? : ELGG_ENTITIES_ANY_VALUE,
	'container_guids' => $container_guids ? : ELGG_ENTITIES_ANY_VALUE,
	'metadata_name_value_pairs' => [
		'name' => 'simpletype',
		'value' => 'image',
	],
	'base_url' => current_page_url(),
	'no_results' => elgg_echo('images:no_results'),
	'list_type_toggle' => true,
	'list_type' => $list_type,
	'list_class' => 'elgg-list-images',
	'gallery_class' => 'elgg-gallery-images',
	'item_view' => 'lists/images/item',
	'size' => 'large',
];

$dbprefix = elgg_get_config('dbprefix');
$user_guid = (int) elgg_get_page_owner_guid();

switch ($filter) {
	case 'friends' :
		$options['wheres'][] = "EXISTS (SELECT 1 FROM {$dbprefix}entity_relationships WHERE guid_one = e.owner_guid AND relationship = 'friend' AND guid_two = $user_guid)";
		break;

	case 'groups' :
		$options['wheres'][] = "EXISTS (SELECT 1 FROM {$dbprefix}entity_relationships WHERE guid_one = $user_guid AND relationship = 'member' AND guid_two = e.container_guid)";
		break;
}

$options = elgg_trigger_plugin_hook('list_options', 'lists/images/all', $vars, $options);

echo elgg_list_entities_from_metadata($options);

<?php

$subtypes = elgg_extract('subtypes', $vars, images_ui_get_subtypes($vars));
$owner_guids = elgg_extract('owner_guids', $vars);
$container_guids = elgg_extract('container_guids', $vars);
$list_type = elgg_extract('list_type', $vars, get_input('list_type', 'list'));
$filter = elgg_extract('filter', $vars);
$options = ['types' => 'object', 'subtypes' => $subtypes, 'owner_guids' => $owner_guids ?: ELGG_ENTITIES_ANY_VALUE, 'container_guids' => $container_guids ?: ELGG_ENTITIES_ANY_VALUE, 'metadata_name_value_pairs' => ['name' => 'simpletype', 'value' => 'image'], 'base_url' => elgg_get_current_url(), 'no_results' => elgg_echo('images:no_results'), 'list_type_toggle' => true, 'list_type' => $list_type, 'list_class' => 'elgg-list-images', 'gallery_class' => 'elgg-gallery-images', 'item_view' => 'lists/images/item', 'size' => 'large'];
$user_guid = (int) elgg_get_page_owner_guid();
switch ($filter) {
    case 'friends':
        $options['wheres'][] = function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($user_guid) {
            $prefix = elgg()->db->getTablePrefix();
            return "{$main_alias}.owner_guid IN (SELECT guid_one FROM {$prefix}entity_relationships WHERE relationship = 'friend' AND guid_two = {$user_guid})";
        };
        break;
    case 'groups':
        $options['wheres'][] = function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($user_guid) {
            $prefix = elgg()->db->getTablePrefix();
            return "{$main_alias}.container_guid IN (SELECT guid_two FROM {$prefix}entity_relationships WHERE relationship = 'member' AND guid_one = {$user_guid})";
        };
        break;
}
$options = elgg_trigger_event_results('list_options', 'lists/images/all', $vars, $options);
echo elgg_list_entities($options);
<?php

elgg_push_context('images/all');

$target_guid = elgg_extract('target_guid', $vars);
$target = get_entity($target_guid);

elgg_push_breadcrumb(elgg_echo('images'), '/images/all');

if ($target) {
	elgg_set_page_owner_guid($target->guid);
	elgg_push_breadcrumb($target->getDisplayName(), "images/all/$target->guid");
	if ($target instanceof ElggUser) {
		elgg_push_context('images/owner');
		$owner_guid = $target->guid;
		$target_guid = ELGG_ENTITIES_ANY_VALUE;
		$title = elgg_echo('images:by', [$target->getDisplayName()]);
		if ($target->guid == elgg_get_logged_in_user_guid()) {
			$filter_context = 'mine';
		}
	} else {
		elgg_push_context('images/container');
		elgg_group_gatekeeper();
		$owner_guid = ELGG_ENTITIES_ANY_VALUE;
		$title = elgg_echo('images:in', [$target->getDisplayName()]);
		elgg_push_breadcrumb($title);
		$filter_context = false;
	}
} else {
	$title = elgg_echo('images:all');
	$target = elgg_get_logged_in_user_entity();
	$filter_context = 'all';
}

if ($target->canWriteToContainer(0, 'object', 'file')) {
	elgg_register_menu_item('title', [
		'name' => 'add',
		'text' => elgg_echo('images:upload'),
		'href' => "/images/add/$target->guid",
		'class' => 'elgg-button elgg-button-action',
	]);
}

$content = elgg_view('lists/images/all', [
	'owner_guids' => $owner_guid,
	'container_guids' => $target_guid,
		]);

if (elgg_is_xhr()) {
	echo $content;
} else {
	$body = elgg_view_layout('content', [
		'content' => $content,
		'title' => $title,
		'filter' => elgg_view('filters/images/all', [
			'filter_context' => $filter_context,
		])
	]);

	echo elgg_view_page($title, $body);
}

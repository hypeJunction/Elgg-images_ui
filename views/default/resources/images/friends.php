<?php

elgg_push_context('images/frieds');

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);
if (!$user) {
	forward('', '403');
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('images'), '/images/all');
elgg_push_breadcrumb($user->getDisplayName(), "/images/owner/$user->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('images:by:friends', [$user->getDisplayName()]);

$filter_context = false;
if ($user->guid == elgg_get_logged_in_user_guid()) {
	$filter_context = 'friends';
}

if ($user->canWriteToContainer(0, 'object', 'file')) {
	elgg_register_menu_item('title', [
		'name' => 'add',
		'text' => elgg_echo('images:upload'),
		'href' => "/images/add/$user->guid",
		'class' => 'elgg-button elgg-button-action',
	]);
}

$content = elgg_view('lists/images/all', [
	'filter' => 'friends',
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

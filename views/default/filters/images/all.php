<?php

$filter_context = elgg_extract('filter_context', $vars, 'all');

$tabs = [
	'all' => '/images/all',
];

if (elgg_is_logged_in()) {
	$user = elgg_get_logged_in_user_entity();
	$tabs['mine'] = "/images/owner/$user->username";
	$tabs['friends'] = "/images/friends/$user->username";
	$tabs['groups'] = "/images/groups/$user->username";
}

foreach ($tabs as $tab => $url) {
	elgg_register_menu_item('filter', [
		'name' => $tab,
		'text' => elgg_echo("$tab"),
		'href' => elgg_normalize_url($url),
		'selected' => $tab == $filter_context,
	]);
}

echo elgg_view_menu('filter', [
	'sort_by' => 'priority',
]);

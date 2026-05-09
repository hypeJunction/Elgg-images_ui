<?php

elgg_push_context('images/upload');
elgg_gatekeeper();
$container_guid = (int) elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$container = $container_guid ? get_entity($container_guid) : null;
if (!$container) {
	throw new \Elgg\Exceptions\Http\EntityNotFoundException();
}

if (!$container->canWriteToContainer(0, 'object', 'file')) {
	throw new \Elgg\Exceptions\Http\EntityPermissionsException();
}

elgg_set_page_owner_guid($container->guid);
elgg_entity_gatekeeper($container->guid);
elgg_push_breadcrumb(elgg_echo('images'), '/images/all');
elgg_push_breadcrumb($container->getDisplayName(), "/images/all/{$container->guid}");
$title = elgg_echo('images:upload');
elgg_push_breadcrumb($title);
if (elgg_is_sticky_form('images/upload')) {
	$sticky_values = elgg_get_sticky_values('images/upload');
	if (is_array($sticky_values)) {
		$vars = array_merge($vars, $sticky_values);
	}
}

$vars['container_guid'] = $container->guid;
$content = elgg_view_form('images/upload', ['enctype' => 'multipart/form-data', 'validate' => true], $vars);
$body = elgg_view_layout('content', ['content' => $content, 'title' => $title, 'filter' => '']);
echo elgg_view_page($title, $body);

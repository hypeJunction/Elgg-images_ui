<?php

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

if (!images()->isImage($entity)) {
	forward('', '404');
}

if (!$entity->canEdit()) {
	forward('', '403');
}

$container = $entity->getContainerEntity();

elgg_set_page_owner_guid($container->guid);

elgg_group_gatekeeper();

elgg_push_breadcrumb(elgg_echo('images'), '/images/all');
if ($container) {
	elgg_push_breadcrumb($container->getDisplayName(), "/images/all/$entity->container_guid");
}
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

$title = elgg_echo('images:edit');
elgg_push_breadcrumb($title);

if (elgg_is_sticky_form('images/upload')) {
	$sticky_values = elgg_get_sticky_values('images/upload');
	if (is_array($sticky_values)) {
		$vars = array_merge($vars, $sticky_values);
	}
}
$vars['entity'] = $entity;

$content = elgg_view_form('images/upload', [
	'enctype' => 'multipart/form-data',
	'validate' => true,
		], $vars);

if (elgg_is_xhr()) {
	echo $content;
} else {
	$body = elgg_view_layout('content', [
		'content' => $content,
		'title' => $title,
		'filter' => elgg_view('filters/images/edit', [
			'filter_context' => 'index',
		]),
	]);
}

echo elgg_view_page($title, $body);

<?php

$size = elgg_extract('size', $vars, 'large');
$full = elgg_extract('full_view', $vars);
$entity = elgg_extract('entity', $vars);

if ($full) {
	echo elgg_view('profile/object/image', $vars);
	return;
}

$show_header = elgg_extract('show_header', $vars);
$show_media = elgg_extract('show_media', $vars);
$show_menu = elgg_extract('show_menu', $vars);

if (!images()->isImage($entity)) {
	return;
}

$content = elgg_view_entity_icon($entity, $size);

if (elgg_in_context('gallery')) {
	$show_header = isset($show_header) ? $show_header : false;
	$show_menu = isset($show_menu) ? $show_menu : false;
} else {
	$show_header = isset($show_header) ? $show_header : true;
	$show_menu = isset($show_menu) ? $show_menu : !elgg_in_context('widgets');
	if (in_array($size, ['tiny', 'small', 'medium'])) {
		$length = 250;
	} else {
		$length = 750;
	}

	$description = elgg_view('output/longtext', [
		'value' => elgg_get_excerpt($entity->description, $length),
		'class' => 'man',
	]);

	$content = elgg_view_image_block($content, $description);
}

$menu = '';
if ($show_menu) {
	$menu = elgg_view_menu('entity', ['entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($show_header) {
	$owner = $entity->getOwnerEntity();
	$owner_link = elgg_view('output/url', [
		'href' => $owner->getURL(),
		'text' => $owner->getDisplayName(),
	]);
	$owner_icon = elgg_view_entity_icon($owner, 'small');
	$author_text = elgg_echo('byline', [$owner_link]);
	$container = $entity->getContainerEntity();
	if ($container && !$container instanceof ElggUser) {
		$container_link = elgg_view('output/url', [
			'href' => $container->getURL(),
			'text' => $container->getDisplayName(),
		]);
		$author_text .= ' '  . elgg_echo('images:container:byline', [$container_link]);
	}
	$date = elgg_view_friendly_time($entity->time_created);

	$subtitle = "$author_text $date";

	$params = [
		'entity' => $entity,
		'metadata' => $menu,
		'subtitle' => $subtitle,
		'content' => $content,
	];

	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $summary);
} else {
	echo $content . $menu;
}
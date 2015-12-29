<?php

$entity = elgg_extract('entity', $vars);
if (!images()->isImage($entity)) {
	return;
}

$owner = $entity->getOwnerEntity();
$owner_link = elgg_view('output/url', [
	'href' => $owner->getURL(),
	'text' => $owner->getDisplayName(),
		]);
$owner_icon = elgg_view_entity_icon($owner, 'small');

$author_text = elgg_echo('byline', [$owner_link]);
$date = elgg_view_friendly_time($entity->time_created);

$subtitle = "$author_text $date";

$metadata = elgg_view_menu('entity', [
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
		]);

$params = [
	'entity' => $entity,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
];

$summary = elgg_view('object/elements/summary', $params);

$body = elgg_view('output/longtext', [
	'value' => $entity->description
		]);

$mimetype = $entity->mimetype ? : $entity->detectMimeType();
list($basetype, $subtype) = explode('/', $mimetype);

$extra = '';
if (elgg_view_exists("file/specialcontent/$mimetype")) {
	$extra = elgg_view("file/specialcontent/$mimetype", $vars);
} else if (elgg_view_exists("file/specialcontent/$basetype/default")) {
	$extra = elgg_view("file/specialcontent/$basetype/default", $vars);
}

$body .= $extra;
$body .= elgg_view_comments($entity);

echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'icon' => $owner_icon,
	'summary' => $summary,
	'body' => $body,
]);

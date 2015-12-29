<?php

$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();
if (!$object || !$subject) {
	return;
}

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->getDisplayName(),
	'class' => 'elgg-river-subject',
));

$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->getDisplayName(),
	'class' => 'elgg-river-object',
));

$action = $item->action_type;
$key = "river:$action:object:file:image";
$summary = elgg_echo($key, array($subject_link, $object_link));

echo elgg_view('river/elements/layout', [
	'item' => $item,
	'summary' => $summary,
	'attachments' => elgg_view('lists/images/item', array(
		'full_view' => false,
		'show_header' => false,
		'show_menu' => false,
		'size' => 'medium',
		'entity' => $object,
	)),
]);

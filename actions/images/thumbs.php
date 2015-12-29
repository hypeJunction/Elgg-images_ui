<?php

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_keys((array) $_REQUEST);
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$entity = get_entity($params->guid);
if (!images()->isImage($entity)) {
	register_error(elgg_echo('images:error:not_found'));
	forward(REFERRER);
}

if (!$entity->canEdit()) {
	register_error(elgg_echo('images:error:permission_denied'));
	forward(REFERRER);
}

foreach (['x1', 'y1', 'x2', 'y2'] as $coord) {
	$value = elgg_extract($coord, $params->crop_coords, 0);
	$entity->$coord = (int) round($value, 0);
}

// Updade image's modified time in order to regenerate thumbs
touch($entity->getFilenameOnFilestore());

if ($entity->save()) {
	system_message(elgg_echo('images:thumbs:success'));
} else {
	register_error(elgg_echo('images:thumbs:error'));
}

forward(REFERRER);

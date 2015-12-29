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

$cropped = images()->crop($entity, $params->crop_coords['x1'], $params->crop_coords['y1'], $params->crop_coords['x2'], $params->crop_coords['y2']);
if ($cropped) {
	// reset cropping coordinates as they no longer represent an area on the original image
	unset($entity->x1);
	unset($entity->y1);
	unset($entity->x2);
	unset($entity->y2);
	system_message(elgg_echo('images:crop:success'));
} else {
	register_error(elgg_echo('images:crop:error'));
}

forward(REFERRER);

<?php

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_unique(array_merge(array_keys($_GET), array_keys($_POST)));
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$entity = $params->guid ? get_entity((int) $params->guid) : null;
if (!images()->isImage($entity)) {
	return elgg_error_response(elgg_echo('images:error:not_found'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('images:error:permission_denied'));
}

$cropped = images()->crop($entity, $params->crop_coords['x1'], $params->crop_coords['y1'], $params->crop_coords['x2'], $params->crop_coords['y2']);
if ($cropped) {
	// reset cropping coordinates as they no longer represent an area on the original image
	$entity->saveIconCoordinates([]);
	return elgg_ok_response('', elgg_echo('images:crop:success'));
}

return elgg_error_response(elgg_echo('images:crop:error'));

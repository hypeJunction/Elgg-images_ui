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

foreach (['x1', 'y1', 'x2', 'y2'] as $coord) {
	$value = elgg_extract($coord, $params->crop_coords, 0);
	$entity->$coord = (int) round($value, 0);
}

// Updade image's modified time in order to regenerate thumbs
touch($entity->getFilenameOnFilestore());

if ($entity->save()) {
	return elgg_ok_response('', elgg_echo('images:thumbs:success'));
}

return elgg_error_response(elgg_echo('images:thumbs:error'));

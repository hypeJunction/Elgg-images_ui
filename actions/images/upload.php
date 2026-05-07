<?php

$params = new stdClass();
$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_unique(array_merge(array_keys($_GET), array_keys($_POST)));
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->{$key} = get_input($key);
	}
}

$entity = get_entity($params->guid);
if ($params->guid && !$entity instanceof ElggFile) {
	return elgg_error_response(elgg_echo('images:error:not_found'));
}

if ($entity instanceof ElggFile) {
	$container = $entity->getContainerEntity();
} else if (isset($params->container_guid)) {
	$container = get_entity($params->container_guid);
} else {
	$container = elgg_get_logged_in_user_entity();
}

if (!$container instanceof ElggEntity) {
	return elgg_error_response(elgg_echo('images:error:not_found'));
}

if (!$entity) {
	$entity = new ElggFile();
	$entity->setSubtype('file');
	$entity->container_guid = $container ? $container->guid : elgg_get_logged_in_user_guid();
}

if (!$entity->canEdit() || !$container->canWriteToContainer(0, $entity->getType(), $entity->getSubtype())) {
	return elgg_error_response(elgg_echo('images:error:permission_denied'));
}

$entity = images()->createFromUpload('upload', $entity);
if (!$entity) {
	return elgg_error_response(elgg_echo('images:upload:error:invalid_file'));
}

$entity->title = $params->title;
$entity->description = $params->description;
$entity->tags = string_to_tag_array((string) $params->tags);
$entity->access_id = isset($params->access_id) ? $params->access_id : (elgg_get_config('default_access') ?? ACCESS_PUBLIC);
if ($entity->save()) {
	if (!$params->guid) {
		elgg_create_river_item(['view' => 'river/object/image', 'action_type' => 'create', 'subject_guid' => elgg_get_logged_in_user_guid(), 'object_guid' => $entity->guid]);
	}

	return elgg_ok_response('', elgg_echo('images:upload:success'), $entity->getURL());
}

return elgg_error_response(elgg_echo('images:upload:error'));

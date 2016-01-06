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
if ($params->guid && !$entity instanceof ElggFile) {
	register_error(elgg_echo('images:error:not_found'));
	forward(REFERRER);
}

if ($entity instanceof ElggFile) {
	$container = $entity->getContainerEntity();
} else if (isset($params->container_guid)) {
	$container = get_entity($params->container_guid);
} else {
	$container = elgg_get_logged_in_user_entity();
}
if (!$container instanceof ElggEntity) {
	register_error(elgg_echo('images:error:not_found'));
	forward(REFERRER);
}

if (!$entity) {
	$entity = new ElggFile();
	$entity->subtype = 'file';
	$entity->container_guid = $container ? $container->guid : elgg_get_logged_in_user_guid();
}

if (!$entity->canEdit() || !$container->canWriteToContainer(0, $entity->getType(), $entity->getSubtype())) {
	register_error(elgg_echo('images:error:permission_denied'));
	forward(REFERRER);
}

$entity = images()->createFromUpload('upload', $entity);

if (!$entity) {
	register_error(elgg_echo('images:upload:error:invalid_file'));
	forward(REFERRER);
}

$entity->title = $params->title;
$entity->description = $params->description;
$entity->tags = string_to_tag_array((string) $params->tags);
$entity->access_id = isset($params->access_id) ? $params->access_id : get_default_access();

if ($entity->save()) {
	if (elgg_is_xhr()) {
		echo json_encode($entity->toObject());
	}
	if (!$params->guid) {
		elgg_create_river_item([
			'view' => 'river/object/image',
			'action_type' => 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $entity->guid,
		]);
	}
	system_message(elgg_echo('images:upload:success'));
	forward($entity->getURL());
} else {
	register_error(elgg_echo('images:upload:error'));
	forward(REFERRER);
}

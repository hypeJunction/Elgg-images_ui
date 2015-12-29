<?php
$entity = elgg_extract('entity', $vars);
if ($entity instanceof hypeJunction\Images\Image) {
	$container = $entity->getContainerEntity();
} else {
	$container_guid = elgg_extract('container_guid', $vars);
	$container = get_entity($container_guid);
}

$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

$upload_limit = elgg_echo('file:upload_limit', [elgg_format_bytes($max_upload)]);
?>
<div class="elgg-text-help">
	<?php echo $upload_limit ?>
</div>
<div>
	<label><?php echo elgg_echo('images:file'); ?></label>
	<?php
	echo elgg_view('input/file', [
		'name' => 'upload',
		'value' => $entity->guid,
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label>
	<?php
	echo elgg_view('input/text', [
		'name' => 'title',
		'value' => elgg_extract('title', $vars, $entity->title),
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php
	echo elgg_view('input/longtext', [
		'name' => 'description',
		'value' => elgg_extract('description', $vars, $entity->description),
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php
	echo elgg_view('input/tags', [
		'name' => 'tags',
		'value' => elgg_extract('tags', $vars, $entity->tags),
	]);
	?>
</div>
<?php
echo elgg_view('input/categories', $vars);
echo elgg_view('input/images/container', [
	'value' => $container->guid,
]);
?>
<div>
	<label><?php echo elgg_echo('access'); ?></label>
	<?php
	echo elgg_view('input/access', [
		'name' => 'access_id',
		'value' => elgg_extract('access_id', $vars, $entity->access_id),
	]);
	?>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity->guid]);
	echo elgg_view('input/submit', ['value' => elgg_echo('save')]);

	if ($entity->guid && $entity->canDelete()) {
		echo elgg_view('output/url', [
			'text' => elgg_echo('delete:this'),
			'href' => "action/delete?guid=$entity->guid",
			'confirm' => true,
			'class' => 'elgg-button elgg-button-delete float-alt',
		]);
	}
	?>
</div>

<?php
$entity = elgg_extract('entity', $vars);
if ($entity instanceof hypeJunction\Images\Image) {
	$container = $entity->getContainerEntity();
} else {
	$container_guid = (int) elgg_extract('container_guid', $vars);
	$container = $container_guid ? get_entity($container_guid) : null;
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
		'value' => $entity ? $entity->guid : null,
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label>
	<?php
	echo elgg_view('input/text', [
		'name' => 'title',
		'value' => elgg_extract('title', $vars, $entity ? $entity->title : ''),
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php
	echo elgg_view('input/longtext', [
		'name' => 'description',
		'value' => elgg_extract('description', $vars, $entity ? $entity->description : ''),
	]);
	?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php
	echo elgg_view('input/tags', [
		'name' => 'tags',
		'value' => elgg_extract('tags', $vars, $entity ? $entity->tags : ''),
	]);
	?>
</div>
<?php
echo elgg_view('input/categories', $vars);
if ($container) {
	echo elgg_view('input/images/container', [
		'value' => $container->guid,
	]);
}
?>
<div>
	<label><?php echo elgg_echo('access'); ?></label>
	<?php
	echo elgg_view('input/access', [
		'name' => 'access_id',
		'value' => elgg_extract('access_id', $vars, $entity ? $entity->access_id : ACCESS_DEFAULT),
	]);
	?>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity ? $entity->guid : null]);
	echo elgg_view('input/submit', ['value' => elgg_echo('save')]);

	if ($entity && $entity->guid && $entity->canDelete()) {
		echo elgg_view('output/url', [
			'text' => elgg_echo('delete:this'),
			'href' => "action/delete?guid=$entity->guid",
			'confirm' => true,
			'class' => 'elgg-button elgg-button-delete float-alt',
		]);
	}
	?>
</div>

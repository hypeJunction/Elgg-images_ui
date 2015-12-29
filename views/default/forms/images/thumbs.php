<?php
$entity = elgg_extract('entity', $vars);

if (!images()->isImage($entity)) {
	return;
}
?>
<div class="elgg-text-help">
	<?php echo elgg_echo('images:crop:instructions') ?>
</div>
<div>
	<?php
	echo elgg_view('input/cropper', [
		'src' => elgg_get_download_url($entity),
		'name' => 'crop_coords',
		'x1' => $entity->x1,
		'y1' => $entity->y1,
		'x2' => $entity->x2,
		'y2' => $entity->y2,
	]);
	?>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity->guid]);
	echo elgg_view('input/submit', ['value' => elgg_echo('images:thumbs')]);
	?>
</div>

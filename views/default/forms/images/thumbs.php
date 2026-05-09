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
		'x1' => elgg_extract('x1', $entity->getIconCoordinates(), 0),
		'y1' => elgg_extract('y1', $entity->getIconCoordinates(), 0),
		'x2' => elgg_extract('x2', $entity->getIconCoordinates(), 0),
		'y2' => elgg_extract('y2', $entity->getIconCoordinates(), 0),
	]);
	?>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity->guid]);
	echo elgg_view('input/submit', ['value' => elgg_echo('images:thumbs')]);
	?>
</div>

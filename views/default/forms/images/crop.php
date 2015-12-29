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
	$src = elgg_get_download_url($entity);
	$info = getimagesize($entity->getFilenameOnFilestore());
	echo elgg_view('input/cropper', [
		'src' => $src,
		'name' => 'crop_coords',
		'x1' => 0,
		'y1' => 0,
		'x2' => $info[0],
		'y2' => $info[1],
		'ratio' => '',
	]);
	?>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity->guid]);
	echo elgg_view('input/submit', ['value' => elgg_echo('images:crop')]);
	?>
</div>

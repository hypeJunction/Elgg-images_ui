<?php

$container_guids = [];
$containers = elgg_trigger_plugin_hook('containers', 'images', $vars, []);
if (is_array($containers) || $containers instanceof ElggBatch) {
	foreach ($containers as $container) {
		if ($container instanceof ElggEntity) {
			$container_guids[$container->guid] = $container->getDisplayName();
		}
	}
}

if (empty($container_guids)) {
	echo elgg_view('input/hidden', [
		'name' => 'container_guid',
		'value' => elgg_extract('value', $vars),
	]);
	return;
}
?>
<div>
	<label><?php echo elgg_echo('images:container') ?></label>
	<?php
	echo elgg_view('input/select', [
		'name' => 'container_guid',
		'options_values' => $container_guids,
		'value' => elgg_extract('value', $vars),
	]);
	?>
</div>

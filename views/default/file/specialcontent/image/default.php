<?php

/**
 * Display an image
 *
 * @uses $vars['entity']
 */
$full = elgg_extract('full_view', $vars);
$file = elgg_extract('entity', $vars);

if (!$full || !$file instanceof ElggFile) {
	return;
}

elgg_load_js('lightbox');
elgg_load_css('lightbox');

$img = elgg_view('output/img', [
	'alt' => $file->getDisplayName(),
	'src' => $file->getIconURL('master'),
	'class' => 'elgg-photo',
		]);

$link = elgg_view('output/url', [
	'text' => $img,
	'href' => elgg_get_download_url($file, true),
	'class' => 'elgg-lightbox-photo',
		]);

echo elgg_format_element('div', [
	'class' => 'file-photo',
		], $link);

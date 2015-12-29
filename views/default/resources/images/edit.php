<?php

elgg_push_context('images/edit');

$tab = get_input('tab', 'index');
$view = "resources/images/edit/$tab";
if (!elgg_view_exists($view)) {
	forward('', '404');
}

echo elgg_view($view, $vars);
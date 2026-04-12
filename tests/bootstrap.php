<?php
/**
 * PHPUnit bootstrap for images_ui plugin tests.
 * Plugin installs at {elgg_root}/mod/images_ui/
 */

// tests/ -> images_ui/ -> mod/ -> elgg_root/
$elggRoot = dirname(dirname(dirname(__DIR__)));

require_once $elggRoot . '/vendor/autoload.php';

// Load Elgg test classes
$testClassesDir = $elggRoot . '/vendor/elgg/elgg/engine/tests/classes';
spl_autoload_register(function ($class) use ($testClassesDir) {
    $file = $testClassesDir . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Plugin autoloader
$pluginRoot = dirname(__DIR__);
if (file_exists($pluginRoot . '/vendor/autoload.php')) {
    require_once $pluginRoot . '/vendor/autoload.php';
}
if (file_exists($pluginRoot . '/autoloader.php')) {
    require_once $pluginRoot . '/autoloader.php';
}

\Elgg\Application::loadCore();

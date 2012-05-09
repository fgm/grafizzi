<?php
/**
 * Autoloader initialization file.
 *
 * Customize according to your own deployment, this is just a sample. Its only
 * goal is to enable PSR0 loading of the required packages:
 *
 * - Monolog
 * - Pimple
 */

$base = realpath(getcwd() . '/..');
// echo "Base: $base\n";
$autoloaderPath = "$base/php_lib/misc/psr0.php";
$autoloadFunction = 'psr0_autoload';

// Add or modify the information for your autoloader.
$paths = array(
    "$base/monolog/src",
    "$base/Pimple/lib",
);

$path = array_reduce($paths, function (&$accu, $item) {
  return "$accu:$item";
}, ini_get('include_path'));
ini_set('include_path', $path);

// You should not need to change code after this line.
require_once $autoloaderPath;
if (!spl_autoload_register($autoloadFunction)) {
  die('Autoloader not added.');
}

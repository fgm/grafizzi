<?php
error_reporting(-1);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use OSInet\Graph\Graph;
use OSInet\Graph\GraphAttribute;

// Replace by the information for the autoloader used in your project
$base = '/home/marand/Dropbox/src/php';
echo "Base: $base\n";
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

$debugLevel = Logger::DEBUG;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', $debugLevel));
$dic = new Pimple(array(
  'logger' => $log,
  'directed' => TRUE,
));

$g = new Graph($dic);
// var_dump($g);

$a = new GraphAttribute($dic, 'rankdir', 'TB');
echo "fin\n";
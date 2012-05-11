<?php
use Monolog\Formatter\JsonFormatter;

error_reporting(-1);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\GraphAttribute;

// Initialize the Composer autoloader.
require 'vendor/autoload.php';

$log = new Logger(basename(__FILE__, '.php'));

// Change the minimum logging level using the Logger:: constants.
$log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

$dic = new Pimple(array(
  'logger' => $log,
  'directed' => TRUE,
));

$g = new Graph($dic);
$log->debug('graph generated', array('graph' => $g));
$g->setName('g');

$rankdir = new GraphAttribute($dic, 'rankdir', 'TB');
$log->debug('rankdir created', array('rankdir' => $rankdir));
$label = new GraphAttribute($dic, 'label', 'Some graph');
$log->debug('label created', array('label' => $label));

$g->setAttribute($rankdir);
$log->debug('rankdir assigned to graph', array(
  'graph' => $g,
  'rankdir' => $rankdir,
));

$g->setAttribute($label);
$log->debug('label assigned to graph', array(
  'graph' => $g,
  'label' => $label,
));

echo $g->build();
$log->debug('done');

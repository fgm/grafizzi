<?php

namespace Grafizzi\Graph;

error_reporting(-1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Initialize the Composer autoloader.
require 'vendor/autoload.php';

$log = new Logger(basename(__FILE__, '.php'));

// Change the minimum logging level using the Logger:: constants.
$log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

$dic = new \Pimple(array(
  'logger' => $log,
  'directed' => FALSE,
));

$g = new Graph($dic);
$log->debug('graph generated', array('graph' => $g));
$g->setName('g');
$g->setDirected(false);

$rankdir = new Attribute($dic, 'rankdir', 'TB');
$log->debug('rankdir created', array('rankdir' => $rankdir));
$label = new Attribute($dic, 'label', 'Some graph');
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

$n1 = new Node($dic, 'n1');
$nodeLabel = new Attribute($dic, 'label', 'Some node');
// print_r($nodeLabel);
$n1->setAttribute($nodeLabel);
$g->addChild($n1);

$n2 = new Node($dic, 'n2');
$nodeLabel = new Attribute($dic, 'label', 'Other node');
// print_r($nodeLabel);
$n2->setAttribute($nodeLabel);
$g->addChild($n2);

$edge = new Edge($dic, $n1, $n2);
$edgeLabel = new Attribute($dic, 'label', 'Close to the edge');
$g->addChild($edge);

echo $g->build();
$log->debug('done');

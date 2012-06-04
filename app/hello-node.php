<?php

/**
 * @file
 * hello-node.php: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

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
  'directed' => false,
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

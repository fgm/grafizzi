<?php

/**
 * @file
 * grafizzi.php: a demo of the Grafizzi library applied to Grafizzi itself.
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

class Grafizzi {
  /**
   * @var Graph
   */
  public $g;

  /**
   * @var Renderer
   */
  public $r;

  /**
   * @var \Pimple
   */
  public $dic;

  public function __construct() {
    $log = new Logger(basename(__FILE__, '.php'));

    // Change the minimum logging level using the Logger:: constants.
    $log->pushHandler(new StreamHandler('php://stderr', Logger::INFO));

    $this->dic = $dic = new \Pimple(array(
      'logger' => $log,
      'directed' => false,
    ));

    $this->g = new Graph($dic, 'grafizzi', array(
      new Attribute($dic, 'rankdir', 'BT'),
    ));
    $this->g->setDirected(true);
    $this->r = new Renderer($dic);
  }

  public function addFilters() {
    $g = $this->g;
    $dic = $this->dic;
    $class = new Attribute($dic, 'shape', 'box');
    $interface = new Attribute($dic, 'shape', 'ellipse');
    $extends = new Attribute($dic, 'label', 'extends');
    $implements = new Attribute($dic, 'label', 'implements');

    $this->g
      ->addChild($fi = new Node($dic, 'FilterInterface', array($interface)))

      ->addChild($af = new Node($dic, 'AbstractFilter', array($class)))
      ->addChild($acf = new Node($dic, 'AbstractCommandFilter', array($class)))
      ->addChild($dot = new Node($dic, 'DotFilter', array($class)))
      ->addChild($sink = new Node($dic, 'SinkFilter', array($class)))
      ->addChild($string = new Node($dic, 'StringFilter', array($class)))

      ->addChild(new Edge($dic, $af, $fi, array($implements)))

      ->addChild(new Edge($dic, $sink, $af, array($extends)))
      ->addChild(new Edge($dic, $string, $af, array($extends)))
      ->addChild(new Edge($dic, $dot, $acf, array($extends)))
      ->addChild(new Edge($dic, $acf, $af, array($extends)));

  }

  public function addData() {
    $this->addFilters();
//  public function addTests();
//  public function addBaseClasses();
  }


  public function render() {
    // 1. Build the DOT source
    $this->r->pipe = $this->g->build();

    // 2. Render it with DOT
    $dotArgs = array(
      '-T' => 'svg',
    );
    $this->r->dot($dotArgs);

    // 2bis. Optional: handle stderr from DOT.
    $stderr = $this->r->pipe['stderr'];
    if (!empty($stderr)) {
      $this->dic['logger']->info($stderr);
    }

    // 3. Return the rendered results.
    $stdout = $this->r->pipe['stdout'];
    return $stdout;
  }
}

// Initialize the graph.
$g = new Grafizzi();
// Add its actual data.
$g->addData();
// Render it.
echo $g->render();

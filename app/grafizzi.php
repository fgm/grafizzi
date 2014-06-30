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
use Pimple\Container;

// Initialize the Composer autoloader.
require __DIR__ . '/../vendor/autoload.php';

class Grafizzi {
  /**
   * @var \Grafizzi\Graph\Graph
   */
  public $g;

  /**
   * @var \Grafizzi\Graph\Renderer
   */
  public $r;

  /**
   * @var \Monolog\Logger
   */
  public $logger;

  /**
   * @var \Pimple\Container
   */
  public $dic;

  public function __construct() {
    $this->logger = new Logger(basename(__FILE__, '.php'));

    // Change the minimum logging level using the Logger:: constants.
    $this->logger->pushHandler(new StreamHandler('php://stderr', Logger::INFO));

    $this->dic = $dic = new Container(array(
      'logger' => $this->logger,
      'directed' => false,
    ));

    $this->g = new Graph($dic, 'grafizzi', array(
      new Attribute($dic, 'rankdir', 'BT'),
    ));
    $this->g->setDirected(true);
    $this->r = new Renderer($dic);
  }

  public function addData() {
    $dic = $this->dic;
    $class = array(new Attribute($dic, 'shape', 'box'));
    $interface = array(new Attribute($dic, 'shape', 'ellipse'));
    $extends = array(new Attribute($dic, 'label', 'extends'));
    $implements = array(new Attribute($dic, 'label', 'implements'));

    $this->g
      ->addChild($fi = new Node($dic, 'FilterInterface', $interface))

      ->addChild($af = new Node($dic, 'AbstractFilter', $class))
      ->addChild($acf = new Node($dic, 'AbstractCommandFilter', $class))
      ->addChild($dot = new Node($dic, 'DotFilter', $class))
      ->addChild($sink = new Node($dic, 'SinkFilter', $class))
      ->addChild($string = new Node($dic, 'StringFilter', $class))

      ->addChild(new Edge($dic, $af, $fi, $implements))

      ->addChild(new Edge($dic, $sink, $af, $extends))
      ->addChild(new Edge($dic, $string, $af, $extends))
      ->addChild(new Edge($dic, $dot, $acf, $extends))
      ->addChild(new Edge($dic, $acf, $af, $extends));
  }

  /**
   * This example does not intercept error output: since this is output via the
   * logger, configure the logger accordingly if error capture is desired.
   */
  public function render() {
    // 1. Build the DOT source
    $this->r->pipe = $this->g->build();

    // 2. Render it with DOT
    $dotArgs = array(
      '-T' => 'svg',
    );
    $this->r->dot($dotArgs);

    // 3. Return the rendered results.
    $stdout = $this->r->pipe;
    return $stdout;
  }
}

// Initialize the graph.
$g = new Grafizzi();
// Add its actual data.
$g->addData();
// Render it.
echo $g->render();

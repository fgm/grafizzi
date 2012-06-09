<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG01Test: a component of the Grafizzi library.
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

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test1.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 1: "Process States in an Operating System Kernel"
 *
 * Graph definition taken from Neato documentation, fig. 1 p. 3
 * "Drawing graphs with NEATO" / Stephen C. North / April 26, 2004
 */
class IG01Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    parent::setUp();
    $this->Graph->setDirected(false);
    $edgeDefinitions = array(
      array('run', 'intr'),
      array('run', 'kernel'),
      array('intr', 'runbl'),
      array('runbl', 'run'),
      array('kernel', 'zombie'),
      array('kernel', 'sleep'),
      array('kernel', 'runmem'),
      array('sleep', 'swap'),
      array('sleep', 'runmem'),
      array('swap', 'runswap'),
      array('runswap', 'new'),
      array('runswap', 'runmem'),
      array('new', 'runmem'),
    );
    foreach ($edgeDefinitions as $edgeDefinition) {
      list($src, $dst) = $edgeDefinition;
      $this->Graph->logger->debug("Adding edge $src -- $dst");
      $srcNode = new Node($this->dic, $src, Node::implicit());
      $this->Graph->addChild($srcNode);
      $dstNode = new Node($this->dic, $dst, Node::implicit());
      $this->Graph->addChild($dstNode);
      $edge = new Edge($this->dic, $srcNode, $dstNode);
      $this->Graph->addChild($edge);
    }
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
graph G {
  run -- intr;
  run -- kernel;
  intr -- runbl;
  runbl -- run;
  kernel -- zombie;
  kernel -- sleep;
  kernel -- runmem;
  sleep -- swap;
  sleep -- runmem;
  swap -- runswap;
  runswap -- new;
  runswap -- runmem;
  new -- runmem;
} /* /graph G */

EOT;
    $this->check($expected, "Image_GraphViz test 1 passed.");
  }
}

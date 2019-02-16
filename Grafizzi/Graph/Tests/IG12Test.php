<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG12Test: a component of the Grafizzi library.
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

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test12.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 12: "Graph of binary search tree"
 *
 * "Graph definition taken from GraphViz documentation"
 */
class IG12Test extends BaseGraphTest {

  public function setUp() : void {
    // not strict by default.
    parent::setUpExtended('structs');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);
    $letters = array('G', 'E', 'B', 'F', 'R', 'H', 'Y', 'A', 'C');

    $nodeShape = new Attribute($dic, 'shape', 'record');
    $nodes = array();
    foreach ($letters as $offset => $letter) {
      $g->addChild($nodes[$offset] = new Node($dic, "node$offset", array(
        $nodeShape,
        new Attribute($dic, 'label', "<f0> |<f1> $letter|<f2> "),
      )));
    }

    $edgeAttributes = array();
    $g->addChild(new Edge($dic, $nodes[0], $nodes[4], $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $nodes[0], $nodes[1], $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $nodes[1], $nodes[2], $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $nodes[1], $nodes[3], $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $nodes[2], $nodes[8], $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $nodes[2], $nodes[7], $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $nodes[4], $nodes[6], $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $nodes[4], $nodes[5], $edgeAttributes, 'f0', 'f1'));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
digraph structs {
  node0 [ shape=record, label="<f0> |<f1> G|<f2> " ];
  node1 [ shape=record, label="<f0> |<f1> E|<f2> " ];
  node2 [ shape=record, label="<f0> |<f1> B|<f2> " ];
  node3 [ shape=record, label="<f0> |<f1> F|<f2> " ];
  node4 [ shape=record, label="<f0> |<f1> R|<f2> " ];
  node5 [ shape=record, label="<f0> |<f1> H|<f2> " ];
  node6 [ shape=record, label="<f0> |<f1> Y|<f2> " ];
  node7 [ shape=record, label="<f0> |<f1> A|<f2> " ];
  node8 [ shape=record, label="<f0> |<f1> C|<f2> " ];
  node0:f2 -> node4:f1;
  node0:f0 -> node1:f1;
  node1:f0 -> node2:f1;
  node1:f2 -> node3:f1;
  node2:f2 -> node8:f1;
  node2:f0 -> node7:f1;
  node4:f2 -> node6:f1;
  node4:f0 -> node5:f1;
} /* /digraph structs */

EOT;
    $this->check($expected, "Image_GraphViz test 12 passed.");
  }
}

<?php

/**
 * @file
 * A recreation of Image_GraphViz test12.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 12: "Graph of binary search tree"
 *
 * "Graph definition taken from GraphViz documentation"
 */

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * Graph test case.
 */
class IG12Test extends BaseGraphTest {

  public function setUp() {
    // not strict by default.
    parent::setUp('structs');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);
    $letters = array('G', 'E', 'B', 'F', 'R', 'H', 'Y', 'A', 'C');
    $nodeShape = new Attribute($dic, 'shape', 'record');
    foreach ($letters as $offset => $letter) {
      $name = "node$offset";
      $g->addChild($$name = new Node($dic, $name, array(
        $nodeShape,
        new Attribute($dic, 'label', "<f0> |<f1> $letter|<f2> "),
      )));
    }

    $edgeAttributes = array();
    $g->addChild(new Edge($dic, $node0, $node4, $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $node0, $node1, $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $node1, $node2, $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $node1, $node3, $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $node2, $node8, $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $node2, $node7, $edgeAttributes, 'f0', 'f1'));
    $g->addChild(new Edge($dic, $node4, $node6, $edgeAttributes, 'f2', 'f1'));
    $g->addChild(new Edge($dic, $node4, $node5, $edgeAttributes, 'f0', 'f1'));
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
    $build = $this->Graph->build();
    $this->Graph->logger->debug("\n\n$build\n\n");
    $this->assertEquals($expected, $build, "Image_GraphViz test 12 passed.");
  }
}

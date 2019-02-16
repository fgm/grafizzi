<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG15943Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test15943.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test for bug #15943: "Nested subgraphs"
 */
class IG15943Test extends BaseGraphTest {

  public function setUp() : void {
    // not strict by default.
    parent::setUpExtended('G', array('strict' => true));
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);

    $nullTitle = array(new Attribute($dic, 'title', null));

    $g->addChild($node5 = new Node($dic, 'node5'));
    $g->addChild($clusterA = new Cluster($dic, 'A'));
    $clusterA->addChild($node0 = new Node($dic, 'node0', $nullTitle));
    $clusterA->addChild($node1 = new Node($dic, 'node1', $nullTitle));
    $clusterA->addChild($clusterB = new Cluster($dic, 'B', array(
      new Attribute($dic, 'label', 'Cluster B'),
    )));

    $clusterB->addChild($node2 = new Node($dic, 'node2', array(
      new Attribute($dic, 'color', 'blue'),
    )));
    $clusterB->addChild($node3 = new Node($dic, 'node3', $nullTitle));

    $clusterA->addChild($clusterC = new Cluster($dic, 'C', $nullTitle));
    $clusterB->addChild($clusterD = new Cluster($dic, 'D', $nullTitle));

    $clusterC->addChild($node4 = new Node($dic, 'node4', $nullTitle));
    $clusterD->addChild($node6 = new Node($dic, 'node6', $nullTitle));

    $g->addChild(new Edge($dic, $node0, $node1));
    $g->addChild(new Edge($dic, $node0, $node4));
    $g->addChild(new Edge($dic, $node2, $node3));
    $g->addChild(new Edge($dic, $node4, $node5));
    $g->addChild(new Edge($dic, $node5, $node6));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
strict digraph G {
  node5;
  subgraph cluster_A {
    node0;
    node1;
    subgraph cluster_B {
      label="Cluster B";

      node2 [ color=blue ];
      node3;
      subgraph cluster_D {
        node6;
      } /* /subgraph cluster_D */
    } /* /subgraph cluster_B */
    subgraph cluster_C {
      node4;
    } /* /subgraph cluster_C */
  } /* /subgraph cluster_A */
  node0 -> node1;
  node0 -> node4;
  node2 -> node3;
  node4 -> node5;
  node5 -> node6;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_GraphViz test 15943 passed.");
  }
}

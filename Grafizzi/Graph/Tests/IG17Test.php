<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\IG17Test: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
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
 * A recreation of Image_GraphViz test17.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 17: "Process diagram with clusters"
 *
 * "Graph definition taken from GraphViz documentation"
 *
 * Note: ordering of insertions differs from Image_GraphViz, since Grafizzi
 * orders output by insertion order to allow customizing output order.
 */
class IG17Test extends BaseGraphCase {

  public function setUp(): void {
    // not strict by default.
    parent::setUpExtended();
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(TRUE);

    $nullTitle = [new Attribute($dic, 'title', NULL)];

    // Global
    $g->addChild($start = new Node($dic, 'start', [
      new Attribute($dic, 'shape', 'Mdiamond'),
    ]));
    $g->addChild($end = new Node($dic, 'end', [
      new Attribute($dic, 'shape', 'Msquare'),
    ]));

    $nodes = [];

    // cluster0
    $g->addChild($cluster0 = new Cluster($dic, '0', [
      new Attribute($dic, 'style', 'filled'),
      new Attribute($dic, 'color', 'lightgrey'),
      new Attribute($dic, 'label', 'process #1'),
    ]));
    for ($i = 0; $i < 4; $i++) {
      $nodeName = "a$i";
      $cluster0->addChild($nodes[$nodeName] = new Node($dic, $nodeName,
        $nullTitle));
    }

    // cluster1
    $g->addChild($cluster1 = new Cluster($dic, '1', [
      new Attribute($dic, 'color', 'blue'),
      new Attribute($dic, 'label', 'process #2'),
    ]));
    for ($i = 0; $i < 4; $i++) {
      $nodeName = "b$i";
      $cluster1->addChild($nodes[$nodeName] = new Node($dic, $nodeName,
        $nullTitle));
    }

    $g->addChild(new Edge($dic, $nodes['a0'], $nodes['a1']));
    $g->addChild(new Edge($dic, $nodes['a1'], $nodes['a2']));
    $g->addChild(new Edge($dic, $nodes['a1'], $nodes['b3']));
    $g->addChild(new Edge($dic, $nodes['a2'], $nodes['a3']));
    $g->addChild(new Edge($dic, $nodes['b0'], $nodes['b1']));
    $g->addChild(new Edge($dic, $nodes['b1'], $nodes['b2']));
    $g->addChild(new Edge($dic, $nodes['b2'], $nodes['b3']));
    $g->addChild(new Edge($dic, $nodes['b2'], $nodes['a3']));

    $g->addChild(new Edge($dic, $start, $nodes['a0']));
    $g->addChild(new Edge($dic, $start, $nodes['b0']));

    $g->addChild(new Edge($dic, $nodes['a3'], $nodes['a0']));
    $g->addChild(new Edge($dic, $nodes['a3'], $end));
    $g->addChild(new Edge($dic, $nodes['b3'], $end));
  }

  /**
   * Tests g->build()
   */
  public function testBuild(): void {
    $expected = <<<'EOT'
digraph G {
  start [ shape=Mdiamond ];
  end [ shape=Msquare ];
  subgraph cluster_0 {
    style=filled;
    color=lightgrey;
    label="process #1";

    a0;
    a1;
    a2;
    a3;
  } /* /subgraph cluster_0 */
  subgraph cluster_1 {
    color=blue;
    label="process #2";

    b0;
    b1;
    b2;
    b3;
  } /* /subgraph cluster_1 */
  a0 -> a1;
  a1 -> a2;
  a1 -> b3;
  a2 -> a3;
  b0 -> b1;
  b1 -> b2;
  b2 -> b3;
  b2 -> a3;
  start -> a0;
  start -> b0;
  a3 -> a0;
  a3 -> end;
  b3 -> end;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 17 passed.");
  }

}

<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG06Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test6.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 6: "Unit test for nodes, subgraphs and clusters using keyword as name"
 *
 * Note: called Test 5 internally in the Image_GraphViz test6.phpt file.
 */
class IG06Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    parent::setUp('G', Graph::strict());
    $graph = &$this->Graph;
    $dic = $this->dic;
    $graph->setName('strict');
    $graph->addChild($graphNode = new Node($dic, 'graph'));

    $graph->addChild($subgraph = new Subgraph($dic, 'subgraph'));
    $subgraph->setAttribute(new Attribute($dic, 'title', ''));

    $graph->addChild($digraph = new Subgraph($dic, 'digraph'));
    $digraph->setName('digraph');
    $digraph->setAttribute(new Attribute($dic, 'title', ''));

    // Note: API difference from Image_GraphViz: the "group" is now defined by
    // the element to which a child is added, not by a separate "group"
    // parameter on constructor.
    $subgraph->addChild($node = new Node($dic, 'node'));
    $digraph->addChild($edge = new Node($dic, 'edge'));

    $graph->addChild(new Edge($dic, $node, $edge));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
strict digraph "strict" {
  "graph";
  subgraph "subgraph" {
    "node";
  } /* /subgraph "subgraph" */
  subgraph "digraph" {
    "edge";
  } /* /subgraph "digraph" */
  "node" -> "edge";
} /* /digraph "strict" */

EOT;
    $this->check($expected, "Image_GraphViz test 6 passed.");
  }
}

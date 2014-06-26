<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG20Test: a component of the Grafizzi library.
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
 * A recreation of Image_GraphViz test20.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 20: "Graph with edges on clusters"
 *
 * "Graph definition taken from GraphViz documentation"
 */
class IG20Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    // not strict by default.
    parent::setUp();
    $graph = $this->Graph;
    $dic = $this->dic;
    $graph->setDirected(true);
    $graph->setAttributes(array(
      new Attribute($dic, 'compound', true),
    ));

    $nullTitle = array(new Attribute($dic, 'title', NULL));
    $nodes = array();

    $graph->addChild($cluster0 = new Cluster($dic, 'cluster0', $nullTitle));
    foreach (array('a', 'b', 'c', 'd') as $name) {
      $cluster0->addChild($nodes[$name] = new Node($dic, $name));
    }

    $graph->addChild($cluster1 = new Cluster($dic, 'cluster1', $nullTitle));
    foreach (array('e', 'f', 'g') as $name) {
      $cluster1->addChild($nodes[$name] = new Node($dic, $name));
    }

    $graph->addChild(new Edge($dic, $nodes['a'], $nodes['b']));
    $graph->addChild(new Edge($dic, $nodes['a'], $nodes['c']));
    $graph->addChild(new Edge($dic, $nodes['b'], $nodes['d']));

    // Note how we use getBuildName() instead of getName() for lhead/ltail,
    // because this are the strings GraphViz expects.
    $graph->addChild(new Edge($dic, $nodes['b'], $nodes['f'], array(
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $nodes['c'], $nodes['d']));
    $graph->addChild(new Edge($dic, $nodes['c'], $nodes['g'], array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $nodes['c'],  $nodes['e'], array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $nodes['e'], $nodes['g']));
    $graph->addChild(new Edge($dic, $nodes['e'], $nodes['f']));
    $graph->addChild(new Edge($dic, $nodes['d'], $nodes['e']));
    $graph->addChild(new Edge($dic, $nodes['d'], $nodes['h'] = new Node($dic, 'h', array('implicit' => true))));
  }

  /**
   * Tests g->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  compound=true;

  subgraph cluster0 {
    a;
    b;
    c;
    d;
  } /* /subgraph cluster0 */
  subgraph cluster1 {
    e;
    f;
    g;
  } /* /subgraph cluster1 */
  a -> b;
  a -> c;
  b -> d;
  b -> f [ lhead=cluster1 ];
  c -> d;
  c -> g [ ltail=cluster0, lhead=cluster1 ];
  c -> e [ ltail=cluster0 ];
  e -> g;
  e -> f;
  d -> e;
  d -> h;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 20 passed.");
  }
}

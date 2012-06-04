<?php

/**
 * @file
 * A recreation of Image_GraphViz test20.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 20: "Graph with edges on clusters"
 *
 * "Graph definition taken from GraphViz documentation"
 *
 * Note: original sample names cluster 0 "cluster0" instead of 'cluster_0'.
 * Although GraphViz supports these run-ins, Grafizzi adds a "_" for legibility.
 * This means cluster names in lhead/ltail attributes had to be changed to match
 * Grafizzi name format.
 */

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * Graph test case.
 */
class IG20Test extends BaseGraphTest {

  public function setUp() {
    // not strict by default.
    parent::setUp();
    $graph = $this->Graph;
    $dic = $this->dic;
    $graph->setDirected(true);
    $graph->setAttributes(array(
      new Attribute($dic, 'compound', true),
    ));


    $nullTitle = array(new Attribute($dic, 'title', NULL));

    $graph->addChild($cluster0 = new Cluster($dic, '0', $nullTitle));
    foreach (array('a', 'b', 'c', 'd') as $name) {
      $cluster0->addChild($$name = new Node($dic, $name));
    }

    $graph->addChild($cluster1 = new Cluster($dic, '1', $nullTitle));
    foreach (array('e', 'f', 'g') as $name) {
      $cluster1->addChild($$name = new Node($dic, $name));
    }

    $graph->addChild(new Edge($dic, $a, $b));
    $graph->addChild(new Edge($dic, $a, $c));
    $graph->addChild(new Edge($dic, $b, $d));

    // Note how we use getBuildName() instead of getName() for lhead/ltail,
    // because this are the strings GraphViz expects.
    $graph->addChild(new Edge($dic, $b, $f, array(
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $c, $d));
    $graph->addChild(new Edge($dic, $c, $g, array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
      new Attribute($dic, 'lhead', $cluster1->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $c,  $e, array(
      new Attribute($dic, 'ltail', $cluster0->getBuildName()),
    )));
    $graph->addChild(new Edge($dic, $e, $g));
    $graph->addChild(new Edge($dic, $e, $f));
    $graph->addChild(new Edge($dic, $d, $e));
    $graph->addChild(new Edge($dic, $d, $h = new Node($dic, 'h', array('implicit' => true))));
  }

  /**
   * Tests g->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  compound=true;

  subgraph cluster_0 {
    a;
    b;
    c;
    d;
  } /* /subgraph cluster_0 */
  subgraph cluster_1 {
    e;
    f;
    g;
  } /* /subgraph cluster_1 */
  a -> b;
  a -> c;
  b -> d;
  b -> f [ lhead=cluster_1 ];
  c -> d;
  c -> g [ ltail=cluster_0, lhead=cluster_1 ];
  c -> e [ ltail=cluster_0 ];
  e -> g;
  e -> f;
  d -> e;
  d -> h;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 20 passed.");
  }
}

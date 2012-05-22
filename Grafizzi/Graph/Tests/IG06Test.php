<?php

/**
 * @file
 * A recreation of Image_GraphViz test6.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 6: "Unit test for nodes, subgraphs and clusters using keyword as name"
 *
 * Note: called Test 5 internally in the Image_GraphViz test6.phpt file.
 */

namespace Grafizzi\Graph\Tests;


use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

require 'vendor/autoload.php';

/**
 * Graph test case.
 */
class IG06Test extends BaseGraphTest {

  public function setUp() {
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
    $build = $this->Graph->build();
    //echo "\n\n$build\n\n";
    $this->assertEquals($expected, $build, "Image_GraphViz test 6 passed.");
  }
}

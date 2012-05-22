<?php

/**
 * @file
 * A recreation of Image_GraphViz test5.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 5: "Unit test for Graph with polygonal shape"
 *
 * "Graph definition taken from GraphViz documentation"
 */

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * Graph test case.
 */
class IG05Test extends BaseGraphTest {

  public function setUp() {
    parent::setUp();
    $this->Graph->setDirected(true);
    $this->Graph->addChild($a = new Node($this->dic, 'a', array(
      new Attribute($this->dic, 'shape', 'polygon'),
      new Attribute($this->dic, 'sides', 5),
      new Attribute($this->dic, 'peripheries', 3),
      new Attribute($this->dic, 'color', 'lightblue'),
      new Attribute($this->dic, 'style', 'filled'),
    )));

    // TODO : support automatic (undeclared) nodes in edges, to remove $b
    $this->Graph->addChild($b = new Node($this->dic, 'b', array('implicit' => true)));
    $this->Graph->addChild($c = new Node($this->dic, 'c', array(
      new Attribute($this->dic, 'shape', 'polygon'),
      new Attribute($this->dic, 'sides', 4),
      new Attribute($this->dic, 'skew', .4),
      new Attribute($this->dic, 'label', 'hello world'),
    )));
    $this->Graph->addChild($d = new Node($this->dic, 'd', array(
      new Attribute($this->dic, 'shape', 'invtriangle'),
    )));
    $this->Graph->addChild($e = new Node($this->dic, 'e', array(
      new Attribute($this->dic, 'shape', 'polygon'),
      new Attribute($this->dic, 'sides', 4),
      new Attribute($this->dic, 'distortion', .7),
    )));
    $this->Graph->addChild(new Edge($this->dic, $a, $b));
    $this->Graph->addChild(new Edge($this->dic, $b, $c));
    $this->Graph->addChild(new Edge($this->dic, $b, $d));

  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
digraph G {
  a [ shape=polygon, sides=5, peripheries=3, color=lightblue, style=filled ];
  c [ shape=polygon, sides=4, skew=0.4, label="hello world" ];
  d [ shape=invtriangle ];
  e [ shape=polygon, sides=4, distortion=0.7 ];
  a -> b;
  b -> c;
  b -> d;
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Image_GraphViz test 5 passed.");
  }
}

<?php

/**
 * @file
 * A recreation of Image_GraphViz test14.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 14: "Drawing of records (revisited)"
 *
 * Graph definition taken from GraphViz documentation
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
class IG14Test extends BaseGraphTest {

  public function setUp() {
    // not strict by default.
    parent::setUp('structs');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);

    $recordShape = new Attribute($dic, 'shape', 'record');
    $g->addChild($struct1 = new Node($dic, 'struct1', array(
      $recordShape,
      new Attribute($dic, 'label', '<f0> left|<f1> middle|<f2> right'),
    )));
    $g->addChild($struct2 = new Node($dic, 'struct2', array(
      $recordShape,
      new Attribute($dic, 'label', '<f0> one|<f1> two'),
    )));
    $g->addChild($struct3 = new Node($dic, 'struct3', array(
      $recordShape,
      new Attribute($dic, 'label', "hello\nworld | { b |{c|<here> d|e}| f}| g | h"),
    )));

    $g->addChild(new Edge($dic, $struct1, $struct2, array(), 'f1', 'f0'));
    $g->addchild(new Edge($dic, $struct1, $struct3, array(), 'f1', 'here'));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph structs {
  struct1 [ shape=record, label="<f0> left|<f1> middle|<f2> right" ];
  struct2 [ shape=record, label="<f0> one|<f1> two" ];
  struct3 [ shape=record, label="hello\nworld | { b |{c|<here> d|e}| f}| g | h" ];
  struct1:f1 -> struct2:f0;
  struct1:f1 -> struct3:here;
} /* /digraph structs */

EOT;
    $build = $this->Graph->build();
    // echo "\n\n$build\n\n";
    $this->assertEquals($expected, $build, "Image_GraphViz test 14 passed.");
  }
}

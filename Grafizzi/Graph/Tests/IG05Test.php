<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG05Test: a component of the Grafizzi library.
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
 * A recreation of Image_GraphViz test5.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 5: "Unit test for Graph with polygonal shape"
 *
 * "Graph definition taken from GraphViz documentation"
 */
class IG05Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
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
    $this->Graph->addChild($b = new Node($this->dic, 'b', Node::implicit()));
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
    $this->check($expected, "Image_GraphViz test 5 passed.");
  }
}

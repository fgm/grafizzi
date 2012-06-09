<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG03Test: a component of the Grafizzi library.
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
 * A recreation of Image_GraphViz test3.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 3: "Drawing of fancy graph"
 *
 * Graph definition taken from Neato documentation, fig. 3 & 4 p. 5
 * "Drawing graphs with dot"
 *   Emden Gansner, Eleftherios Koutsofios and Stephen North / January 26, 2006
 *
 * Setup logic and order of result lines slightly modified to account for the
 * API change: nodes must be created before they are referenced, be they
 * implicit or explicit in the resulting graph.
 */
class IG03Test extends BaseGraphTest {

  public function setUp($name = 'G', $attributes = array()) {
    parent::setUp();
    $this->Graph->setDirected(true);
    $graph = &$this->Graph;
    $dic = &$this->dic;

    $graph->addChild($main = new Node($dic, 'main', array(
      new Attribute($dic, 'shape', 'box'),
      new Attribute($dic, 'comment', 'this is a comment'),
    )));
    $graph->addChild(new Edge($dic, $main,
      $parse = new Node($this->dic, 'parse'), array(
        new Attribute($dic, 'weight', 8)
    )));
    $graph->addChild(new Edge($dic,
      $parse = new Node($dic, 'parse', Node::implicit()),
      $execute = new Node($dic, 'execute', Node::implicit())
    ));
    $graph->addChild(new Edge($dic, $main,
      $init = new Node($dic, 'init', Node::implicit()), array(
        new Attribute($dic, 'style', 'dotted')
    )));
    $graph->addChild(new Edge($dic, $main,
      $cleanup = new Node($dic, 'cleanup', Node::implicit())
    ));

    // XXX The original example creates the node after the edge referencing it.
    $graph->addChild($make_string = new Node($dic, 'make_string', array(
      new Attribute($dic, 'label', "make a\nstring"),
    )));

    $graph->addChild(new Edge($dic, $execute, $make_string));
    $graph->addChild(new Edge($dic, $execute,
      $printf = new Node($dic, 'printf', Node::implicit())
    ));
    $graph->addChild(new Edge($dic, $init, $make_string));
    $graph->addChild(new Edge($dic, $main, $printf, array(
      new Attribute($dic, 'style', 'bold'),
      new Attribute($dic, 'label', '100 times'),
    )));

    $graph->addChild($compare = new Node($dic, 'compare', array(
      new Attribute($dic, 'shape', 'box'),
      new Attribute($dic, 'style', 'filled'),
      new Attribute($dic, 'color', '.7 .3 1.0'),
    )));

    $graph->addChild(new Edge($dic, $execute, $compare, array(
      new Attribute($dic, 'color', 'red'),
      new Attribute($dic, 'comment', 'so is this'),
    )));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  main [ shape=box, comment="this is a comment" ];
  main -> parse [ weight=8 ];
  parse -> execute;
  main -> init [ style=dotted ];
  main -> cleanup;
  make_string [ label="make a\nstring" ];
  execute -> make_string;
  execute -> printf;
  init -> make_string;
  main -> printf [ style=bold, label="100 times" ];
  compare [ shape=box, style=filled, color=".7 .3 1.0" ];
  execute -> compare [ color=red, comment="so is this" ];
} /* /digraph G */

EOT;
    $this->check($expected, "Image_GraphViz test 3 passed.");
  }
}

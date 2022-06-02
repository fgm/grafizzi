<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\IG14Test: a component of the Grafizzi library.
 *
 * (c) 2012-2022 Frédéric G. MARAND <fgm@osinet.fr>
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
 * A recreation of Image_GraphViz test14.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 14: "Drawing of records (revisited)"
 *
 * Graph definition taken from GraphViz documentation
 */
class IG14Test extends BaseGraphTest {

  public function setUp(): void {
    // not strict by default.
    parent::setUpExtended('structs');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(TRUE);

    $recordShape = new Attribute($dic, 'shape', 'record');
    $g->addChild($struct1 = new Node($dic, 'struct1', [
      $recordShape,
      new Attribute($dic, 'label', '<f0> left|<f1> middle|<f2> right'),
    ]));
    $g->addChild($struct2 = new Node($dic, 'struct2', [
      $recordShape,
      new Attribute($dic, 'label', '<f0> one|<f1> two'),
    ]));
    $g->addChild($struct3 = new Node($dic, 'struct3', [
      $recordShape,
      new Attribute($dic, 'label',
        "hello\nworld | { b |{c|<here> d|e}| f}| g | h"),
    ]));

    $g->addChild(new Edge($dic, $struct1, $struct2, [], 'f1', 'f0'));
    $g->addchild(new Edge($dic, $struct1, $struct3, [], 'f1', 'here'));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild(): void {
    $expected = <<<'EOT'
digraph structs {
  struct1 [ shape=record, label="<f0> left|<f1> middle|<f2> right" ];
  struct2 [ shape=record, label="<f0> one|<f1> two" ];
  struct3 [ shape=record, label="hello\nworld | { b |{c|<here> d|e}| f}| g | h" ];
  struct1:f1 -> struct2:f0;
  struct1:f1 -> struct3:here;
} /* /digraph structs */

EOT;
    $this->check($expected, "Image_GraphViz test 14 passed.");
  }

}

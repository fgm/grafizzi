<?php

/**
 * @file
 * Grafizzi\Graph\Tests\MultiEdgeTest: a component of the Grafizzi library.
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
use Grafizzi\Graph\MultiEdge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A test for Grafizzi "Multiedges".
 *
 * No equivalent in Image_GraphViz.
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Graph definition taken from Neato documentation, fig. 5 p. 6
 * "Drawing graphs with NEATO" / Stephen C. North / April 26, 2004
 */
class MultiEdgeTest extends BaseGraphTest {

  /**
   *
   * @var MultiEdge
   */
  private $MultiEdge;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();

    $this->Graph->setDirected(false);
    $nodes = array();
    for ($i = 0 ; $i < 4 ; $i++) {
      $this->Graph->addChild($nodes[] = new Node($this->dic, "n$i", Node::implicit()));
    }

    // Test case is n0 -- n1 -- n2 -- n3 -- n0, so append n0
    $nodes[] = $nodes[0];
    $this->MultiEdge = new MultiEdge($this->dic, $nodes);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->MultiEdge = null;
    parent::tearDown();
  }

  /**
   * Tests MultiEdge->build()
   */
  public function testBuild() {
    // Test unbound multiedge.
$expected = <<<EOT
n0 -- n1 -- n2 -- n3 -- n0;

EOT;
    $build = $this->MultiEdge->build(false);
    $this->assertEquals($expected, $build, "Unbound undirected multiedge without attributes built correctly.");

    // Test bound multiedge.
    $this->Graph->setDirected(false);
    $this->Graph->addChild($this->MultiEdge);
    $expected = <<<EOT
  n0 -- n1 -- n2 -- n3 -- n0;

EOT;
    $build = $this->MultiEdge->build();
    $this->assertEquals($expected, $build, "Undirected multiedge bound to root graph without attributes built correctly.");

    // Test full graph build.
    $expected = <<<EOT
graph G {
  n0 -- n1 -- n2 -- n3 -- n0;
} /* /graph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Graph with undirected multiedge without attributes built correctly.");

    // Test full graph with attributes.
    $this->MultiEdge->setAttributes(array(
      new Attribute($this->dic, 'label', 'Multiedge label'),
    ));
    $expected = <<<EOT
graph G {
  n0 -- n1 -- n2 -- n3 -- n0 [ label="Multiedge label" ];
} /* /graph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Graph with undirected multiedge with attributes built correctly.");
  }

  // Normal attributes list on unbound multiedge.
  public function testBuildAttributesNormal() {
    $this->MultiEdge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
n0 -- n1 -- n2 -- n3 -- n0 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->MultiEdge->build(false);
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title in middle on unbound multiedge.
  public function testBuildAttributesEmptyMiddle() {
    $this->MultiEdge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'title', ''),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
    $expected = <<<EOT
n0 -- n1 -- n2 -- n3 -- n0 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->MultiEdge->build(false);
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as single attribute on unbound multiedge.
  public function testBuildAttributesOnlyEmpty() {
    $this->MultiEdge->setAttributes(array(
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
n0 -- n1 -- n2 -- n3 -- n0;

EOT;
    $actual = $this->MultiEdge->build(false);
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as last attribute on unbound multiedge.
  public function testBuildAttributesEmptyLast() {
    $this->MultiEdge->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
      new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
n0 -- n1 -- n2 -- n3 -- n0 [ foo=bar, baz=quux ];

EOT;
    $actual = $this->MultiEdge->build(false);
    $this->assertEquals($expected, $actual);
  }

  /**
   * Tests MultiEdge::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes() {
    $this->assertEmpty(MultiEdge::getAllowedChildTypes(), 'MultiEdge does not support children.');
  }

  /**
   * Tests MultiEdge->getType()
   */
  public function testGetType() {
    $this->assertEquals('multiedge', $this->MultiEdge->getType());
  }
}

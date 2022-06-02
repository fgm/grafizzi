<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\GraphTest: a component of the Grafizzi library.
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

require 'vendor/autoload.php';

use \Grafizzi\Graph\Attribute;
use \Grafizzi\Graph\Graph;

/**
 * Graph test case.
 */
class GraphTest extends BaseGraphTest {

  public function setUp(): void {
    parent::setUpExtended();
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild(): void {
    $graph = $this->Graph->build();
    $this->assertEquals(<<<EOT
digraph G {
} /* /digraph G */

EOT
      , $graph, 'Empty unnamed graph matches expected format.');
  }

  // Normal attributes list.
  public function testBuildAttributesNormal(): void {
    $this->Graph->setAttributes([
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
    ]);
    $expected = <<<EOT
digraph G {
  foo=bar;
  baz=quux;
} /* /digraph G */

EOT;
    $actual = $this->Graph->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title in middle.
  public function testBuildAttributesEmptyMiddle(): void {
    $this->Graph->setAttributes([
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'title', ''),
      new Attribute($this->dic, 'baz', 'quux'),
    ]);
    $expected = <<<EOT
digraph G {
  foo=bar;
  baz=quux;
} /* /digraph G */

EOT;
    $actual = $this->Graph->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as single attribute.
  public function testBuildAttributesOnlyEmpty(): void {
    $this->Graph->setAttributes([
      new Attribute($this->dic, 'title', ''),
    ]);
    $expected = <<<EOT
digraph G {
} /* /digraph G */

EOT;
    $actual = $this->Graph->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as last attribute.
  public function testBuildAttributesEmptyLast(): void {
    $this->Graph->setAttributes([
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
      new Attribute($this->dic, 'title', ''),
    ]);
    $expected = <<<EOT
digraph G {
  foo=bar;
  baz=quux;
} /* /digraph G */

EOT;
    $actual = $this->Graph->build();
    $this->assertEquals($expected, $actual);
  }

  /**
   * Tests Graph::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes(): void {
    $types = Graph::getAllowedChildTypes();
    $this->assertTrue(is_array($types) && count($types) == 5,
      'Five child types allowed for Graphs.');
    ksort($types);
    $expectedTypes = [
      'cluster',
      'edge',
      'multiedge',
      'node',
      'subgraph',
    ];
    $this->assertEquals($expectedTypes, $types);
  }

  /**
   * Tests Graph->getDirected()
   */
  public function testGetDirected(): void {
    $dir = $this->Graph->getDirected();
    $this->assertEquals(TRUE, $dir, "Graph is directed by default.");
  }

  /**
   * Tests Graph::getType()
   */
  public function testGetType(): void {
    $this->Graph->setDirected(FALSE);
    $type = $this->Graph->getType();
    $this->assertEquals('graph', $type, 'Graph type is "graph".');

    $this->Graph->setDirected(TRUE);
    $type = $this->Graph->getType();
    $this->assertEquals('digraph', $type, 'Graph type is "digraph".');
  }

  /**
   * Tests Graph->setDirected()
   */
  public function testSetDirected(): void {
    $this->Graph->setDirected(FALSE);
    $this->assertFalse($this->Graph->getDirected(),
      "Setting directed to false changes the result of getDirected().");
  }

}

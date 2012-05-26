<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use \Grafizzi\Graph\Attribute;
use \Grafizzi\Graph\Graph;

/**
 * Graph test case.
 */
class GraphTest extends BaseGraphTest {

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $graph = $this->Graph->build();
    $this->assertEquals(<<<EOT
digraph G {
} /* /digraph G */

EOT
      , $graph, 'Empty unnamed graph matches expected format.');
  }

  // Normal attributes list.
  public function testBuildAttributesNormal() {
    $this->Graph->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
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
  public function testBuildAttributesEmptyMiddle() {
    $this->Graph->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'title', ''),
      new Attribute($this->dic, 'baz', 'quux'),
    ));
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
  public function testBuildAttributesOnlyEmpty() {
    $this->Graph->setAttributes(array(
        new Attribute($this->dic, 'title', ''),
    ));
    $expected = <<<EOT
digraph G {
} /* /digraph G */

EOT;
    $actual = $this->Graph->build();
    $this->assertEquals($expected, $actual);
  }

  // Attribute list with empty title as last attribute.
  public function testBuildAttributesEmptyLast() {
    $this->Graph->setAttributes(array(
      new Attribute($this->dic, 'foo', 'bar'),
      new Attribute($this->dic, 'baz', 'quux'),
      new Attribute($this->dic, 'title', ''),
    ));
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
  public function testGetAllowedChildTypes() {
    $types = Graph::getAllowedChildTypes();
    $this->assertTrue(is_array($types) && count($types) == 5,
      'Five child types allowed for Graphs.');
    ksort($types);
    $expectedTypes = array(
      'cluster',
      'edge',
      'multiedge',
      'node',
      'subgraph',
    );
    $this->assertEquals($expectedTypes, $types);
  }

  /**
   * Tests Graph->getDirected()
   */
  public function testGetDirected() {
    $dir = $this->Graph->getDirected();
    $this->assertEquals(true, $dir, "Graph is directed by default.");
  }

  /**
   * Tests Graph::getType()
   */
  public function testGetType() {
    $this->Graph->setDirected(false);
    $type = $this->Graph->getType();
    $this->assertEquals('graph', $type, 'Graph type is "graph".');

    $this->Graph->setDirected(true);
    $type = $this->Graph->getType();
    $this->assertEquals('digraph', $type, 'Graph type is "digraph".');
  }

  /**
   * Tests Graph->setDirected()
   */
  public function testSetDirected() {
    $this->Graph->setDirected(false);
    $this->assertFalse($this->Graph->getDirected(),
      "Setting directed to false changes the result of getDirected().");
  }
}

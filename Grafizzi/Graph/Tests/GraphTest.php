<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

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
}

EOT
      , $graph, 'Empty unnamed graph matches expected format.');
  }

  /**
   * Tests Graph::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes() {
    $types = Graph::getAllowedChildTypes();
    $this->assertTrue(is_array($types) && count($types) == 4,
      'Four child types allowed for Graphs.');
    ksort($types);
    $expectedTypes = array(
      'cluster',
      'edge',
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
    $type = Graph::getType();
    $this->assertEquals('graph', $type, 'Graph type is "graph".');
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

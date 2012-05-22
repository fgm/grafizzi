<?php

/**
 * @file
 * A test for Grafizzi "Multiedges".
 *
 * No equivalent in Image_GraphViz.
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Graph definition taken from Neato documentation, fig. 5 p. 6
 * "Drawing graphs with NEATO" / Stephen C. North / April 26, 2004
 */


namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\MultiEdge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';


/**
 * MultiEdge test case.
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

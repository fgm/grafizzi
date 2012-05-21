<?php

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Edge;

require 'vendor/autoload.php';

use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

/**
 * Subgraph test case.
 */
class SubgraphTest extends BaseGraphTest {

  /**
   * @var Subgraph
   */
  private $Subgraph;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $this->Subgraph = new Subgraph($this->dic);
    $this->Subgraph->setName('sub');
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->Subgraph = null;
    parent::tearDown();
  }

  public function testBuild() {
    $n11 = new Node($this->dic, 'node11');
    $n12 = new Node($this->dic, 'node12');
    $e11_12 = new Edge($this->dic, $n11, $n12);
    foreach (array($n11, $n12, $e11_12) as $element) {
      $this->Subgraph->addChild($element);
    }
    $expected = <<<EOT
subgraph sub {
  node11;
  node12;
  node11 -> node12;
} /* /subgraph sub */

EOT;

    $build = $this->Subgraph->build();
    $this->assertEquals($expected, $build, 'Unbound subgraph (2 nodes, 1 edge) built correctly.');

    // Test bound subgraph build.
    $this->Graph->addChild($this->Subgraph);
    $expected = <<<EOT
  subgraph sub {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph sub */

EOT;
    $build = $this->Subgraph->build();
    $this->assertEquals($expected, $build, 'Bound subgraph (2 nodes, 1 edge) built correctly.');

    // Test graph with bound subgraph.
    $expected = <<<EOT
digraph G {
  subgraph sub {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph sub */
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, 'Graph with 1 subgraph (2 nodes, 1 edge) built correctly.');
  }

  public function testGetBuildName() {
    $buildName = $this->Subgraph->getBuildName();
    $this->assertEquals('sub', $buildName, 'Name of subgraph matched the one from setName().');
  }

  /**
   * Tests Subgraph->getType()
   */
  public function testGetType() {
    $type = $this->Subgraph->getType();
    $this->assertEquals('subgraph', $type, 'Type of subgraph is "subgraph".');
  }
}


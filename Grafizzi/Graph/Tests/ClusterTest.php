<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Edge;

/**
 * Cluster test case.
 */
class ClusterTest extends BaseGraphTest {

  /**
   * @var Cluster
   */
  private $Cluster;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $this->Cluster = new Cluster($this->dic);
    $this->Cluster->setName('foo');
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->Cluster = null;
    parent::tearDown();
  }

  public function testBuild() {
    $n11 = new Node($this->dic, 'node11');
    $n12 = new Node($this->dic, 'node12');
    $e11_12 = new Edge($this->dic, $n11, $n12);
    foreach (array($n11, $n12, $e11_12) as $element) {
      $this->Cluster->addChild($element);
    }
    $expected = <<<EOT
subgraph cluster_foo {
  node11;
  node12;
  node11 -> node12;
} /* /subgraph cluster_foo */

EOT;

    $build = $this->Cluster->build();
    $this->assertEquals($expected, $build, 'Unbound cluster (2 nodes, 1 edge) built correctly.');

    // Test bound subgraph build.
    $this->Graph->addChild($this->Cluster);
    $expected = <<<EOT
  subgraph cluster_foo {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph cluster_foo */

EOT;
    $build = $this->Cluster->build();
    $this->assertEquals($expected, $build, 'Bound cluster (2 nodes, 1 edge) built correctly.');

    // Test graph with bound subgraph.
    $expected = <<<EOT
digraph G {
  subgraph cluster_foo {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph cluster_foo */
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, 'Graph with 1 cluster (2 nodes, 1 edge) built correctly.');
  }

  /**
   * Tests Cluster->getBuildName()
   */
  public function testGetBuildName() {
    $buildName = $this->Cluster->getBuildName();
    $this->assertEquals('cluster_foo', $buildName, 'Cluster name matches the expected format.');
  }

  /**
   * Tests Cluster->getType()
   */
  public function testGetType() {
    $type = $this->Cluster->getType();
    $this->assertEquals('subgraph', $type, 'Cluster type is "subgraph".');
  }
}
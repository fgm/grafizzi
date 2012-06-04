<?php

/**
 * @file
 * Grafizzi\Graph\Tests\ClusterTest: a component of the Grafizzi library.
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

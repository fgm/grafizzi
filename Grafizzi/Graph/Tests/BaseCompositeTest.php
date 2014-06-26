<?php

/**
 * @file
 * Grafizzi\Graph\Tests\BaseCompositeTest: a component of the Grafizzi library.
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

use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

/**
 * Abstract base class for SubgraphTest/ClusterTest.
 */
abstract class BaseCompositeTest extends BaseGraphTest {

  /**
   * @var string
   *   The generate name for the subgraph.
   */
  protected $name;

  /**
   * @var Subgraph
   */
  protected $Subgraph;

  /**
   * @var string
   *   The type of subgraph being tested.
   */
  protected $type;

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
subgraph {$this->name} {
  node11;
  node12;
  node11 -> node12;
} /* /subgraph {$this->name} */

EOT;

    $build = $this->Subgraph->build();
    $this->assertEquals($expected, $build, "Unbound {$this->type} (2 nodes, 1 edge) built correctly.");

    // Test bound subgraph build.
    $this->Graph->addChild($this->Subgraph);
    $expected = <<<EOT
  subgraph {$this->name} {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph {$this->name} */

EOT;
    $build = $this->Subgraph->build();
    $this->assertEquals($expected, $build, "Bound {$this->type} (2 nodes, 1 edge) built correctly.");

    // Test graph with bound subgraph.
    $expected = <<<EOT
digraph G {
  subgraph {$this->name} {
    node11;
    node12;
    node11 -> node12;
  } /* /subgraph {$this->name} */
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Graph with 1 {$this->type} (2 nodes, 1 edge) built correctly.");
  }

  /**
   * Tests Subgraph->getBuildName()
   */
  public function testGetBuildName() {
    $buildName = $this->Subgraph->getBuildName();
    $this->assertEquals($this->name, $buildName, "{$this->type} name matches the expected format.");
  }

  /**
   * Tests Subgraph->getType()
   */
  public function testGetType() {
    $type = $this->Subgraph->getType();
    $this->assertEquals('subgraph', $type, "Type of {$this->type} is \"subgraph\".");
  }
}

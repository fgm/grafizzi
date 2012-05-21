<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\Cluster;

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

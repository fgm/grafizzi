<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

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


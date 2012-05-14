<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use Grafizzi\Graph\Node;

/**
 * Node test case.
 */
class NodeTest extends BaseGraphTest {

  /**
   *
   * @var Node
   */
  private $Node;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();

    $this->Node = new Node($this->dic, 'n1');
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    // TODO Auto-generated NodeTest::tearDown()
    $this->Node = null;

    parent::tearDown();
  }

  /**
   * Tests Node->__construct()
   */
  public function test__construct() {
    $this->assertEquals('n1', $this->Node->getName());
  }

  /**
   * Tests Node->build()
   */
  public function testBuild() {
    $this->Graph->addChild($this->Node);
    $dot = $this->Graph->build();
    $this->assertEquals(<<<EOT
digraph G {
  n1;
} /* /graph */

EOT
      , $dot, "Graph with a single node built correctly.");
  }

  /**
   * Tests Node::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes() {
    $this->assertEmpty(Node::getAllowedChildTypes());
  }

  /**
   * Tests Node::getType()
   */
  public function testGetType() {
    $this->assertEquals('node', Node::getType());
  }
}


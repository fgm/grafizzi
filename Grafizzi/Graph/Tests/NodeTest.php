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
    // Test unbound node build.
    $expected = <<<EOT
n1;

EOT;
    $build = $this->Node->build();
    $this->assertEquals($expected, $build, 'Unbound node built correctly.');

    // Test build of node built at level 1.
    $this->Graph->addChild($this->Node);
    $expected = <<<EOT
  n1;

EOT;
    $build = $this->Node->build();
    $this->assertEquals($expected, $build, "Node bound at level 1 built correctly.");

    // Test build of node within a root graph.
    $expected = <<<EOT
digraph G {
  n1;
} /* /digraph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Graph with a single node built correctly.");
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

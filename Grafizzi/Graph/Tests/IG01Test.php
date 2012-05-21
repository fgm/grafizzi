<?php

/**
 * @file
 * A recreation of Image_GraphViz test1.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 1: "Process States in an Operating System Kernel"
 *
 * Graph definition taken from Neato documentation, fig. 1 p. 3
 * "Drawing graphs with NEATO" / Stephen C. North / April 26, 2004
 */

namespace Grafizzi\Graph\Tests;

use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';


/**
 * Graph test case.
 */
class IG01Test extends BaseGraphTest {

  public function setUp() {
    parent::setUp();
    $this->Graph->setDirected(FALSE);
    $edgeDefinitions = array(
      array('run', 'intr'),
      array('run', 'kernel'),
      array('intr', 'runbl'),
      array('runbl', 'run'),
      array('kernel', 'zombie'),
      array('kernel', 'sleep'),
      array('kernel', 'runmem'),
      array('sleep', 'swap'),
      array('sleep', 'runmem'),
      array('swap', 'runswap'),
      array('runswap', 'new'),
      array('runswap', 'runmem'),
      array('new', 'runmem'),
    );
    foreach ($edgeDefinitions as $edgeDefinition) {
      list($src, $dst) = $edgeDefinition;
      $this->Graph->logger->debug("Adding edge $src -- $dst");
      $srcNode = new Node($this->dic, $src);
      $dstNode = new Node($this->dic, $dst);
      $edge = new Edge($this->dic, $srcNode, $dstNode);
      $this->Graph->addChild($edge);
    }
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
graph G {
  run -- intr;
  run -- kernel;
  intr -- runbl;
  runbl -- run;
  kernel -- zombie;
  kernel -- sleep;
  kernel -- runmem;
  sleep -- swap;
  sleep -- runmem;
  swap -- runswap;
  runswap -- new;
  runswap -- runmem;
  new -- runmem;
} /* /graph G */

EOT;
    $build = $this->Graph->build();
    $this->assertEquals($expected, $build, "Image_GraphViz test 1 passed.");
  }
}

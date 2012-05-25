<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

/**
 * Static test suite.
 */
class BaseTestsSuite extends \PHPUnit_Framework_TestSuite {

  /**
   * Extend PHPUnit addTestSuite to add test classes from the current namespace.
   *
   * @see PHPUnit_Framework_TestSuite::addTestSuite()
   */
  public function addTestSuite($testClass) {
    parent::addTestSuite(__NAMESPACE__ . "\\$testClass");
  }

  /**
   * Constructs the test suite handler.
   */
  public function __construct() {
    $this->setName('BaseTestsSuite');

    $this->addTestSuite('AttributeTest');
    $this->addTestSuite('ClusterTest');
    $this->addTestSuite('EdgeTest');
    $this->addTestSuite('GraphTest');
    $this->addTestSuite('MultiEdgeTest');
    $this->addTestSuite('NodeTest');
    $this->addTestSuite('SubgraphTest');
    $this->addTestSuite('escapeTest');

    // Image_GraphViz tests adapted for Grafizzi.
    $igTests = array('01', '03', '05', '06', '09');

    foreach ($igTests as $igTest) {
      $this->addTestSuite("IG{$igTest}Test");
    }
  }

  /**
   * Creates the suite.
   */
  public static function suite() {
    return new self();
  }
}

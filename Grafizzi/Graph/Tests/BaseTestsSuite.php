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
    $this->addTestSuite('GraphTest');
    $this->addTestSuite('NodeTest');
    $this->addTestSuite('EdgeTest');
    $this->addTestSuite('MultiEdgeTest');
    $this->addTestSuite('SubgraphTest');
    $this->addTestSuite('ClusterTest');

    // Image_GraphViz tests adapted for Grafizzi.
    $igTests = array('01', '05');

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

<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

/**
 * Static test suite.
 */
class BaseTestsSuite extends \PHPUnit_Framework_TestSuite {

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
  }

  /**
   * Creates the suite.
   */
  public static function suite() {
    return new self();
  }
}

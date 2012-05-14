<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

use \Grafizzi\Graph\Graph;

/**
 * Base test case.
 *
 * Build a graph that all other test cases will need.
 */
class BaseGraphTest extends \PHPUnit_Framework_TestCase {

  /**
   *
   * @var Graph
   */
  public $Graph;

  /**
   * @var Pimple
   */
  public $dic;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();

    $log = new Logger(basename(__FILE__, '.php'));
    $log->pushHandler(new StreamHandler('php://stderr', Logger::INFO));
    $this->dic = new \Pimple(array(
        'logger' => $log,
    ));
    $this->Graph = new Graph($this->dic);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->Graph = null;
    parent::tearDown();
  }
}


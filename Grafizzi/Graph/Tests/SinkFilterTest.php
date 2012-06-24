<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\Filter\SinkFilter;

/**
 * SinkFilter test case.
 */
class SinkFilterTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var SinkFilter
   */
  private $sinkFilter;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $this->sinkFilter = new SinkFilter();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->sinkFilter = null;
    parent::tearDown();
  }

  /**
   * Tests SinkFilter->filter()
   */
  public function testFilter() {
    $in = 'Sink test';
    $out = $this->sinkFilter->filter($in);
    $this->assertNull($out, 'Sink filter returns NULL output.');
  }
}

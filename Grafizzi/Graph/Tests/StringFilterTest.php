<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\Filter\StringFilter;

/**
 * StringFilter test case.
 */
class StringFilterTest extends \PHPUnit_Framework_TestCase {

  /**
   *
   * @var StringFilter
   */
  private $stringFilters = array();

  /**
   * String to copy to.
   *
   * @var string
   */
  private $out = 'initial data';

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $this->stringFilters[] = new StringFilter();
    $args = array(
      'out' => &$this->out,
      'callback' => function ($x) { return strrev($x) ; },
    );
    $this->stringFilters[] = new StringFilter($args);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->stringFilters = null;
    parent::tearDown();
  }

  /**
   * Tests StringFilter->filter()
   */
  public function testFilter() {
    $in = 'String test';
    $out = $this->stringFilters[0]->filter($in);
    $this->assertEquals($in, $out, 'String filter returns its input.');

    $out = $this->stringFilters[1]->filter($in);
    $expected = strrev($in);
    $this->assertEquals($expected, $out, 'String filter with callback applies it.');
    $this->assertEquals($expected, $this->out, 'String filter with out string assigns it.');
  }
}


<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\AbstractNamed;

/**
 * escape() test case.
 */
class escapeTest extends BaseGraphTest {

  /**
   * Tests escape()
   */
  public function testEscape() {
    $testSet = array(
      'foo' => 'foo',
      'foo bar' => '"foo bar"',
      "foo'bar" => '"foo\'bar"',
      'foo"bar' => '"foo\"bar"',
    );

    foreach ($testSet as $in => $expected) {
      $actual = AbstractNamed::escape($in);
      $this->assertEquals($expected, $actual);
    }
  }
}


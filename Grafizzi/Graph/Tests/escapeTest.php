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
      // Newline in non-pseudo-HTML mode: escaped
      "foo\nbar" => '"foo\nbar"',
    );

    foreach ($testSet as $in => $expected) {
      $actual = AbstractNamed::escape($in);
      $this->assertEquals($expected, $actual);
    }
  }

  public function testEscapePseudoHtml() {
    $testEscaped = array(
      // Test pseudo-HTML label: needs to be <>-wrapped.
      '<b>Label</b>' => '<<b>Label</b>>',
      // Test non-pseudo-HTML, non-ID label: needs to be dquote-wrapped.
      'Non HTML' => '"Non HTML"',
      // Test non-pseudo-HTML, ID label: needs not be wrapped.
      'nmtoken' => 'nmtoken',
      // Newline in pseudo-HTML: not converted
      "<b>One\nTwo</b>" => "<<b>One\nTwo</b>>",
    );
    foreach ($testEscaped as $in => $expected) {
      $actual = AbstractNamed::escape($in, true);
      $this->assertEquals($expected, $actual);
    }
  }
}


<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\escapeTest: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\AbstractNamed;

/**
 * escape() test case.
 */
class escapeTest extends BaseGraphCase {

  /**
   * Tests escape()
   */
  public function testEscape(): void {
    $testSet = [
      'foo' => 'foo',
      'foo bar' => '"foo bar"',
      "foo'bar" => '"foo\'bar"',
      'foo"bar' => '"foo\"bar"',
      // Newline in non-pseudo-HTML mode: escaped
      "foo\nbar" => '"foo\nbar"',
    ];

    foreach ($testSet as $in => $expected) {
      $actual = AbstractNamed::escape($in);
      $this->assertEquals($expected, $actual);
    }
  }

  public function testEscapePseudoHtml():void {
    $testEscaped = [
      // Test pseudo-HTML label: needs to be <>-wrapped.
      '<b>Label</b>' => '<<b>Label</b>>',
      // Test non-pseudo-HTML, non-ID label: needs to be dquote-wrapped.
      'Non HTML' => '"Non HTML"',
      // Test non-pseudo-HTML, ID label: needs not be wrapped.
      'nmtoken' => 'nmtoken',
      // Newline in pseudo-HTML: not converted
      "<b>One\nTwo</b>" => "<<b>One\nTwo</b>>",
    ];
    foreach ($testEscaped as $in => $expected) {
      $actual = AbstractNamed::escape($in, TRUE);
      $this->assertEquals($expected, $actual);
    }
  }

}
